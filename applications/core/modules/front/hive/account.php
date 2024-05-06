<?php
/**
 * @brief		account
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community

 * @since		21 Aug 2023
 */

namespace IPS\core\modules\front\hive;

use IPS\core\Hive;
use IPS\Db;
use IPS\Http\Url;
use IPS\Member;
use IPS\Output;
use IPS\Session;
use IPS\Settings;
use IPS\Theme;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * account
 */
class _account extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		Output::i()->error( 'node_error', '2HV100/2', 404, '' );
	}
	
		/**
	 * Follow Community
	 *
	 * @return void
	 */
	protected function follow()
	{
		/* Check Hive is Enabled */
		if( empty( Settings::i()->hive_key ) )
		{
			Output::i()->error( 'node_error', '2HV100/3', 404, '' );
		}

		if( Member::loggedIn()->member_id )
		{
			$form = new \IPS\Helpers\Form( 'hive', 'hive_subscribe_button' );
			$form->class = 'ipsForm_vertical ipsType_center ipsForm_noLabels';
			$form->add( new \IPS\Helpers\Form\Email( 'hive_subscriber_email_address', Member::loggedIn()->email ?? '', TRUE, [ 'placeholder' => Member::loggedIn()->language()->addToStack('email_address')] ) );

			if ( $values = $form->values() )
			{
				Session::i()->csrfCheck();

				try
				{
					$response = Hive::api( 'subscribe', [
						'site_member_id' => Member::loggedIn()->member_id ?? 0,
						'group_hash' => Member::loggedin()->member_id ? Hive::groupHash( Member::loggedin() ) : 'guest',
						'member_email' => $values['hive_subscriber_email_address'],
					] );

					/* Save subscribe */
					if ( Member::loggedIn()->member_id )
					{
						try
						{
							Db::i()->insert( 'core_hive_subscribers', [ 'member_id' => Member::loggedIn()->member_id, 'subscribe_date' => time() ] );
						}
						catch ( Db\Exception $e ){}
					}

					if ( !empty( $response['redirect_url'] ) )
					{
						Output::i()->redirect( Url::external( $response['redirect_url'] ) );
					}
				}
				catch ( \IPS\Http\Url\Exception|\LogicException $e )
				{
					$form->error = $e->getMessage();
				}
			}
		}
		else
		{
			$form = Theme::i()->getTemplate( 'hive', 'core', 'front' )->signin();
		}

		Output::i()->title = Member::loggedIn()->language()->addToStack('hive_subscribe_to_hive', NULL, [ 'sprintf' => Settings::i()->board_name ]);
		Output::i()->output = Theme::i()->getTemplate( 'hive', 'core', 'front' )->follow( $form );
	}

	/**
	 * Anonymously follow community
	 *
	 * @return void
	 */
	protected function anonymousFollow()
	{
		Output::i()->pageCaching = FALSE;

		/* Check Hive is Enabled */
		if( empty( Settings::i()->hive_key ) )
		{
			Output::i()->error( 'node_error', '2HV100/4', 404, '' );
		}

		try
		{
			$response = Hive::api( 'subscribe', [
				'site_member_id' => 0,
				'group_hash' => 'guest',
				'member_email' => false,
			] );

			/* Save subscribe */
			if ( !empty( $response['redirect_url'] ) )
			{
				Output::i()->redirect( Url::external( $response['redirect_url'] ) );
			}
			else
			{
				throw new \LogicException( 'unknown_error' );
			}
		}
		catch ( \IPS\Http\Url\Exception|\LogicException $e )
		{
			Output::i()->error( $e->getMessage(), '2HV100/1', 403, '' );
		}
	}
}