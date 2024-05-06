<?php
/**
 * @brief		Uninstall callback
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Topic Password Protection
 * @since		02 Jun 2022
 */

namespace IPS\tpp\extensions\core\Uninstall;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Uninstall callback
 */
class _topicPassword
{
	/**
	 * Code to execute before the application has been uninstalled
	 *
	 * @param	string	$application	Application directory
	 * @return	array
	 */
	public function preUninstall( $application )
	{
	}

	/**
	 * Code to execute after the application has been uninstalled
	 *
	 * @param	string	$application	Application directory
	 * @return	array
	 */
	public function postUninstall( $application )
	{
		\IPS\Db::i()->delete( 'core_content_meta', array( "meta_type=?", 'tpp_tpp' ) );
	}

	/**
	 * Code to execute when other applications or plugins are uninstalled
	 *
	 * @param	string	$application	Application directory
	 * @param	int		$plugin			Plugin ID
	 * @return	void
	 */
	public function onOtherUninstall( $application=NULL, $plugin=NULL )
	{
	}
}