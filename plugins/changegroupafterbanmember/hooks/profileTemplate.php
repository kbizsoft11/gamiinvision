//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook90 extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'profile' => 
  array (
    0 => 
    array (
      'selector' => '#elProfileInfoColumn > div.ipsPadding.ipsBox.ipsResponsive_pull',
      'type' => 'add_inside_start',
      'content' => '{template="lastWarnProfileBlock" group="plugins" location="global" app="core" params="$member"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
