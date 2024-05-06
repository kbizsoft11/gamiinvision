<?php
/**
 * @brief		Community Enhancements
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community

 * @since		21 Aug 2023
 */

namespace IPS\core\extensions\core\CommunityEnhancements;

use IPS\core\Hive;
use IPS\Db;
use IPS\Http\Url;
use IPS\Login;
use IPS\Member;
use IPS\Output;
use IPS\Settings;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Community Enhancement
 */
class _Hive
{
	/**
	 * @brief	Enhancement is enabled?
	 */
	public $enabled	= FALSE;

	/**
	 * @brief	IPS-provided enhancement?
	 */
	public $ips	= TRUE;

	/**
	 * @brief	Enhancement has configuration options?
	 */
	public $hasOptions	= TRUE;

	/**
	 * @brief	Icon data
	 */
	public $icon	= "communityhive.svg";
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->enabled = ( !empty( Settings::i()->hive_enabled ) AND !empty( Settings::i()->hive_key ) AND !empty( Settings::i()->hive_site_id ) );
	}
	
	/**
	 * Edit
	 *
	 * @return	void
	 */
	public function edit()
	{
		$hostname = \defined( 'COMMUNITYHIVE_URL' ) ? COMMUNITYHIVE_URL : 'https://communityhive.com';
		$output = '';

		if( empty( Settings::i()->hive_key ) OR ! Hive::isSiteIdValid() )
		{
			$form = new \IPS\Helpers\Form('hive', 'enable' );
			$form->add( new \IPS\Helpers\Form\Text( 'hive_community_name', Settings::i()->board_name, TRUE ) );
			$form->add( new \IPS\Helpers\Form\Email( 'hive_email_address', Member::loggedIn()->email, TRUE ) );
			if ( $values = $form->values() )
			{
				try
				{
					$randomKey = Login::generateRandomString( 40 );
					$request = Url::external( $hostname . '/api/v1/activate')
						->request()
						->put( json_encode([
							'site_name' => $values['hive_community_name'],
							'site_url' => Settings::i()->base_url,
							'site_api_url' => (string) Url::internal('app=core&module=hive&controller=content', 'front', 'hive_content'),
							'site_key' => $randomKey,
							'site_email' => $values['hive_email_address'],
							'site_software' => 'invisioncommunity',
							'hive_system_version' => \IPS\Application::load('core')->version
						]) );
						
					if( (int) $request->httpResponseCode !== 200 )
					{
						throw new \LogicException( $request->decodeJson()['error'] ?? 'There was an issue connecting to Community Hive.' );
					}

					$response = $request->decodeJson();
					
					Settings::i()->changeValues([
						'hive_key' => $response['hive_key'],
						'hive_site_id' => $response['hive_site_id'],
						'hive_site_key' => $randomKey,
						'hive_enabled' => TRUE
					]);

					/* Subscribe site manager */
					$response = Hive::api( 'subscribe', [
						'site_member_id' => Member::loggedIn()->member_id,
						'group_hash' => Hive::groupHash( Member::loggedin() ),
						'member_email' => $values['hive_email_address'],
					]);

					/* Save subscribe */
					try
					{
						Db::i()->insert( 'core_hive_subscribers', [ 'member_id' => Member::loggedIn()->member_id, 'subscribe_date' => time() ] );
					}
					catch( Db\Exception $e ) {}

					$redirectUrl = Url::external( $response['redirect_url'] );
					$output = \IPS\Theme::i()->getTemplate( 'hive', 'core', 'admin' )->wrapper( 'activated', null, $redirectUrl );
				}
				catch ( \LogicException $e )
				{
					$form->error = $e->getMessage();
					$output = \IPS\Theme::i()->getTemplate( 'hive', 'core', 'admin' )->wrapper( 'activation', $form );
				}
			}
			else
			{
				$output = \IPS\Theme::i()->getTemplate( 'hive', 'core', 'admin' )->wrapper( 'activation', $form );
			}
		}
		else
		{
			$form = new \IPS\Helpers\Form('hive', 'save' );

			/* Work out all the different classes */
			$classes = array();

			foreach ( \IPS\Content::routedClasses( TRUE, FALSE, TRUE ) as $class )
			{
				if ( is_subclass_of( $class, 'IPS\Content\Searchable' ) AND isset( $class::$databaseColumnMap['date'] ) )
				{
					$classes[ $class ] = $class::$title . '_pl';
				}
			}

			$settings = json_decode( Settings::i()->hive_content, TRUE );

			/* Work out all the different classes */
			$classes = array();
			$classToggles = array();

			foreach ( \IPS\Content::routedClasses( TRUE, FALSE, TRUE ) as $class )
			{
				if ( is_subclass_of( $class, 'IPS\Content\Searchable' ) AND isset( $class::$databaseColumnMap['date'] ) )
				{
					$bits = explode( '\\', $class );
					if( method_exists( $class, 'database' ) )
					{
						$label = $class::database()->_title . ' - ' . Member::loggedIn()->language()->addToStack( $class::$title . '_pl' );
					}
					else
					{
						$label = Member::loggedIn()->language()->addToStack( '__app_' . $bits[1] ) . ' - ' . Member::loggedIn()->language()->addToStack( $class::$title . '_pl' );
					}
					$classes[ $class ] = $label;
					if ( isset( $class::$containerNodeClass ) )
					{
						$classToggles[ $class ][] = 'hive_containers_' . $class::$title;
					}
				}
			}

			$containers = $settings['content_containers'] ?? [];

			/* Add the fields for them */
			$default = (!isset( $settings['content_classes'] ) OR $settings['content_classes'] === '*') ? '*' : explode( ',', $settings['content_classes'] );

			$form->add( new \IPS\Helpers\Form\CheckboxSet( 'hive_content_classes', $default, NULL, array( 'options' => $classes, 'unlimited' => '*', 'unlimitedLang' => 'all', 'impliedUnlimited' => TRUE, 'toggles' => $classToggles  ), NULL, NULL, NULL, 'hive_classes' ) );

			/* Nodes */
			foreach ( $classToggles as $class => $id )
			{
				if ( isset( $class::$containerNodeClass ) )
				{
					$nodeClass = $class::$containerNodeClass;
					$value = isset( $containers[ $class ] ) ? implode( ',', $containers[ $class ] ) : 0;

					$field = new \IPS\Helpers\Form\Node( 'hive_containers_' . $class::$title, $value, FALSE, array( 'class' => $nodeClass, 'clubs' => TRUE, 'multiple' => TRUE, 'zeroVal' => 'all', 'permissionCheck' => $nodeClass::searchableNodesPermission(), 'forceOwner' => FALSE, 'subnodes' => FALSE ), NULL, NULL, NULL, 'hive_containers_' . $class::$title );

					if( method_exists( $class, 'database' ) )
					{
						$field->label = $class::database()->_title . ' - ' . Member::loggedIn()->language()->addToStack( $nodeClass::$nodeTitle );
					}
					else
					{
						$field->label = Member::loggedIn()->language()->addToStack( $class::$title . '_pl' ) . ' - ' . Member::loggedIn()->language()->addToStack( $nodeClass::$nodeTitle );
					}
					$form->add( $field );
				}
			}

			if( $values = $form->values() )
			{
				if ( isset( $values['hive_content_classes'] ) )
				{
					$containers = NULL;
					foreach ( ( $values['hive_content_classes'] == '*' ? array_keys( $classes ) : $values['hive_content_classes'] ) as $class )
					{
						if ( isset( $values[ 'hive_containers_' . $class::$title ] ) and $values[ 'hive_containers_' . $class::$title ] )
						{
							if( \is_string( $values[ 'hive_containers_' . $class::$title ] ) )
							{
								$containers[ $class ] = explode( ',', $values[ 'hive_containers_' . $class::$title ] );
							}
							else
							{
								$containers[ $class ] = array_keys( $values[ 'hive_containers_' . $class::$title ] );
							}
						}
					}

					$settings['content_containers'] = $containers;
				}

				$settings['content_classes'] = \is_array( $values['hive_content_classes'] ) ? implode( ',', $values['hive_content_classes'] ) : $values['hive_content_classes'];
				Settings::i()->changeValues([ 'hive_content' => json_encode( $settings ) ]);
				Output::i()->inlineMessage	= Member::loggedIn()->language()->addToStack('saved');
			}

			$output = \IPS\Theme::i()->getTemplate( 'hive', 'core', 'admin' )->wrapper( 'config', $form );
		}
		
		Output::i()->sidebar['actions'] = array(
			'help'	=> array(
				'title'		=> 'help',
				'icon'		=> 'question-circle',
				'link'		=> Url::external( $hostname ),
				'target'	=> '_blank'
			)
		);
		
		Output::i()->output = $output;
	}
	
	/**
	 * Enable/Disable
	 *
	 * @param	$enabled	bool	Enable/Disable
	 * @return	void
	 * @throws	\LogicException
	 */
	public function toggle( $enabled )
	{
		Settings::i()->changeValues([ 'hive_enabled' => (bool) $enabled ]);

		if( empty( Settings::i()->hive_key ) OR empty( Settings::i()->hive_site_id ) )
		{
			throw new \LogicException( 'error' );
		}
		
	}
	
	/**
	 * Test Settings
	 *
	 * @return	void
	 * @throws	\LogicException
	 */
	protected function testSettings()
	{
		if ( FALSE )
		{
			throw new \LogicException( 'error' );
		}
	}
}