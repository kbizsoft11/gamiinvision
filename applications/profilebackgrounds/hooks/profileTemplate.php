//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class profilebackgrounds_hook_profileTemplate extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'profileHeader' => 
  array (
    0 => 
    array (
      'selector' => '#elEditProfile',
      'type' => 'add_inside_start',
      'content' => '{template="profileHeaderButton" app="profilebackgrounds" group="profile" params="$member"}',
    ),
    1 => 
    array (
      'selector' => 'header[data-role=\'profileHeader\']',
      'type' => 'add_before',
      'content' => '{template="profileHeaderCss" app="profilebackgrounds" group="profile" params="$member"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
