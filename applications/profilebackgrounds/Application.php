<?php
/**
 * @brief		Profile Backgrounds Application Class
 * @author		<a href='https://invisioncommunity.com/profile/114025-adriano-faria/'>Adriano Faria</a>
 * @copyright	(c) 2021 Adriano Faria
 * @package		Invision Community
 * @subpackage	Profile Backgrounds
 * @since		14 Sep 2021
 * @version		
 */

namespace IPS\profilebackgrounds;

/**
 * Profile Backgrounds Application Class
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
		return 'picture-o';
	}

	/**
	 * Install 'other' items.
	 *
	 * @return void
	 */
	public function installOther()
	{
		try
		{
			$pluginData = \IPS\Db::i()->select( '*', 'core_plugins', array( 'plugin_location=?', "profilebackgrounds" ) )->first();
			try
			{
				$plugin = \IPS\Plugin::load( $pluginData['plugin_id'] );
				$plugin->delete();
			}
			catch ( \Exception $e ){}
		}
		catch( \UnderflowException $e ){}
	}
}