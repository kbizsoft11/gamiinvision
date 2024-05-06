<?php
/**
 * @brief		Topic Password Protection Application Class
 * @author		<a href='https://invisioncommunity.com/profile/114025-adriano-faria/'>Adriano Faria</a>
 * @copyright	(c) 2022 Adriano Faria
 * @package		Invision Community
 * @subpackage	Topic Password Protection
 * @since		31 May 2022
 * @version		
 */
 
namespace IPS\tpp;

/**
 * Topic Password Protection Application Class
 */
class _Application extends \IPS\Application
{
	/**
	 * [Node] Get Icon for tree
	 *
	 * @note	Return the class for the icon (e.g. 'globe')
	 * @return	string|null
	 */
	protected function get__icon()
	{
		return 'key';
	}

	/**
	 * Install 'other' items.
	 *
	 * @return void
	 */
	public function installOther()
	{
		if( \IPS\Db::i()->checkForColumn( 'forums_topics', 'password' ) AND \IPS\Db::i()->select( 'count(*)', 'core_plugins', array( 'plugin_location=?', 'topicpasswordprotection' ) )->first() )
		{
			$settings = array(
				0  => array( 'tpp_groups', 'tpp_app_groups' ),
				1  => array( 'tpp_groups_exempt', 'tpp_app_groups_exempt' ),
				2  => array( 'tpp_forums', 'tpp_app_forums' ),
				3  => array( 'tpp_log', 'tpp_app_log' ),
				4  => array( 'tpp_forcelogin', 'tpp_app_forcelogin' ),
				5  => array( 'tpp_cookie_days', 'tpp_app_cookie_days' )
			);
			foreach( $settings as $k => $setting )
			{
				try
				{
					$toUpdate = \IPS\Db::i()->select( '*', 'core_sys_conf_settings', array( array( 'conf_key=?', $setting[0] ) ) )->first();
					\IPS\Settings::i()->changeValues( array( $setting[1] => $toUpdate['conf_value'] ) );
				}
				catch( \UnderflowException $e ){}
			}

			\IPS\Application::load( 'tpp' )->enabled = 1;
			\IPS\Application::load( 'tpp' )->save();
			\IPS\Task::queue( 'tpp', 'topicPasswordsFromPlugin', array(), 1 );
		}
	}
}