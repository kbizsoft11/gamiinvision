//<?php

class hook1 extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'whosOnline' => 
  array (
    0 => 
    array (
      'selector' => 'div.ipsWidget_inner',
      'type' => 'add_inside_end',
      'content' => '{{if settings.gni_whos_online == 1}}
{template="gni" group="plugins" location="global" app="core" params="$orientation"}
{{endif}}',
    ),
  ),
  'activeUsers' => 
  array (
    0 => 
    array (
      'selector' => 'div.ipsWidget_inner',
      'type' => 'add_inside_end',
      'content' => '{{if settings.gni_recently_browsing == 1}}
{template="gni" group="plugins" location="global" app="core" params="$orientation"}
{{endif}}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */





}