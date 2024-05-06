//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class tpp_hook_topicTemplate extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'topic' => 
  array (
    0 => 
    array (
      'selector' => '#elClubContainer > div.ipsClearfix > ul.ipsToolList.ipsToolList_horizontal.ipsClearfix.ipsSpacer_both',
      'type' => 'add_inside_end',
      'content' => '{template="tpp_passwordMenu" group="topic" location="front" app="tpp" params="$topic"}',
    ),
    1 => 
    array (
      'selector' => '#elClubContainer > div.ipsPageHeader.ipsResponsive_pull.ipsBox.ipsPadding.sm:ipsPadding:half.ipsMargin_bottom > div.ipsFlex.ipsFlex-ai:stretch.ipsFlex-jc:center > div.ipsFlex-flex:11 > div.ipsFlex.ipsFlex-ai:center.ipsFlex-fw:wrap.ipsGap:4 > div.ipsFlex-flex:11 > h1.ipsType_pageTitle.ipsContained_container',
      'type' => 'add_inside_start',
      'content' => '{template="tpp_passwordBadgeTopic" group="topic" location="front" app="tpp" params="$topic"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
