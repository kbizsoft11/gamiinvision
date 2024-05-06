<?php
/**
 * @brief		bimchatbox Widget
 * @author		<a href='http://www.invisionpower.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) 2001 - SVN_YYYY Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/legal/standards/
 * @package		IPS Social Suite
 * @subpackage	bimchatbox
 * @since		02 Mar 2015
 * @version		SVN_VERSION_NUMBER
 */

namespace IPS\bimchatbox\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * bimchatbox Widget
 */
class _bimchatbox extends \IPS\Widget
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'bimchatbox';
	
	/**
	 * @brief	App
	 */
	public $app = 'bimchatbox';
		
	/**
	 * @brief	Plugin
	 */
	public $plugin = '';
	
	/**
	 * Initialise this widget
	 *
	 * @return void
	 */ 
	public function init()
	{
		parent::init();
		// Use this to perform any set up and to assign a template that is not in the following format:
		// $this->template( array( \IPS\Theme::i()->getTemplate( 'widgets', $this->app, 'front' ), $this->key ) );
	}


	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render()
	{		
		if ( \IPS\Settings::i()->chatbox_conf_on == 1 && \IPS\Application::load('bimchatbox')->can_View() )
		{			
			\IPS\Application::load('bimchatbox')->loadChatbox();
			return $this->output();		
		}

		return "";
	}
}