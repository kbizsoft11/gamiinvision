<?php
/**
 * @brief		bdGroupLegend Widget
 * @author		<a href='http://www.invisionpower.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) 2001 - SVN_YYYY Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/legal/standards/
 * @package		IPS Social Suite
 * @subpackage	bdGroupLegend
 * @since		21 Mar 2015
 * @version		SVN_VERSION_NUMBER
 */

namespace IPS\plugins\grouplegend\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * bdGroupLegend Widget
 */
class _bdGroupLegend extends \IPS\Widget
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'bdGroupLegend';
	
	/**
	 * @brief	App
	 */
	public $app = 'core';
		
	/**
	 * @brief	Plugin
	 */
	public $plugin = '2';
	
	/**
	 * Initialise this widget
	 *
	 * @return void
	 */ 
	public function init()
	{
		// Use this to perform any set up and to assign a template that is not in the following format:
		// $this->template( array( \IPS\Theme::i()->getTemplate( 'widgets', $this->app, 'front' ), $this->key ) );
		// For a plugin:
		// $this->template( array( \IPS\Theme::i()->getTemplate( 'plugins', 'core', 'global' ), $this->key ) );
		
		
		parent::init();
        $this->template( array( \IPS\Theme::i()->getTemplate( 'plugins', 'core', 'global' ), $this->key ) );
	}
	
	/**
	 * Specify widget configuration
	 *
	 * @param	null|\IPS\Helpers\Form	$form	Form object
	 * @return	null|\IPS\Helpers\Form
	 */
	public function configuration( &$form=null )
	{
 		if ( $form === null )
		{
	 		$form = new \IPS\Helpers\Form;
 		}

 		// $$form->add( new \IPS\Helpers\Form\XXXX( .... ) );
 		// return $form;
 	} 
 	
 	 /**
 	 * Ran before saving widget configuration
 	 *
 	 * @param	array	$values	Values from form
 	 * @return	array
 	 */
 	public function preConfig( $values )
 	{
 		return $values;
 	}

	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render()
	{
        if (\IPS\Settings::i()->bd_gl_visibleToGroups != '*' && !\IPS\Member::loggedIn()->inGroup(explode( ',',\IPS\Settings::i()->bd_gl_visibleToGroups)) ) {
			return "";
		}
        
        $selectedGroups  = (empty(\IPS\Settings::i()->bd_gl_groupSort) ? array() : explode( ',', \IPS\Settings::i()->bd_gl_groupSort ));
        $allGroups = \IPS\Member\Group::groups();

        $groupList=array();
        if (!empty($selectedGroups)) {
            foreach($selectedGroups as $g_id) {
                if (array_key_exists($g_id, $allGroups)) {
                    $groupList[]=$allGroups[$g_id];
                }
            }
        }
        else {
            $groupList = $allGroups;
        }

      return $this->output($groupList);  
	}
}