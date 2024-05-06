//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class tpp_hook_topicRowTemplate extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'topicRow' => 
  array (
    0 => 
    array (
      'selector' => 'li.ipsDataItem > div.ipsDataItem_main > h4.ipsDataItem_title.ipsContained_container',
      'type' => 'add_inside_start',
      'content' => '{template="tpp_passwordBadgeForum" group="topic" location="front" app="tpp" params="$row"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
