//<?php

$groups = \IPS\Member\Group::groups();
$list = array_filter(explode( ',', \IPS\Settings::i()->bd_gl_groupSort ));

if (count($list)==0) {
    $i=0;
    $groupList=array();
    foreach($groups as $group) {
        $stackList[]  = array($group->get_name()=>$i);
        $groupList[]  = $group->get_name();
        $groupList_Id[]  = $group->g_id;
        $i++;
    }
}
else {
   foreach($groups as $group) {
       $groupList[]  = $group->get_name();
       $groupList_Id[]  = $group->g_id;
    }

    $stackList = array();
    foreach($list as $l) {
        $stackList[] = array_search($l, $groupList_Id);
    }
}

$form->addTab('bd_gl_tab_general');
$form->add( new \IPS\Helpers\Form\Select( 'bd_gl_visibleToGroups', \IPS\Settings::i()->bd_gl_visibleToGroups=='*' ? '*' : explode( ',', \IPS\Settings::i()->bd_gl_visibleToGroups ), TRUE, array( 'options' => $groups, 'parse' => 'normal', 'multiple' => true, 'unlimited' => '*', 'unlimitedLang' => 'everyone' ), NULL, NULL, NULL, 'bd_gl_visibleToGroups' ) );
$form->add( new \IPS\Helpers\Form\YesNo( 'bd_gl_linkSearch', \IPS\Settings::i()->bd_gl_linkSearch,TRUE));
$form->add( new \IPS\Helpers\Form\Editor( 'bd_gl_text', \IPS\Settings::i()->bd_gl_text, FALSE, array('app' => 'core', 'key' => 'Admin','autoSaveKey'=>'bd-group-legend-text')) );

$form->addTab('bd_gl_tab_grouplist');
$form->add( new \IPS\Helpers\Form\Stack( 'bd_gl_groupSort', $stackList , FALSE, array('stackFieldType'=>'Select','removeEmptyValues'=>false,'options' => $groupList)));

$form->addTab('bd_gl_tab_display');
$form->add( new \IPS\Helpers\Form\YesNo( 'bd_gl_formatName', \IPS\Settings::i()->bd_gl_formatName,TRUE));
$form->add( new \IPS\Helpers\Form\Text( 'bd_gl_globalPrefix', \IPS\Settings::i()->bd_gl_globalPrefix));
$form->add( new \IPS\Helpers\Form\Text( 'bd_gl_globalSuffix', \IPS\Settings::i()->bd_gl_globalSuffix));



if ( $values = $form->values(TRUE) )
{
    $finalOrder=explode( ',', $values['bd_gl_groupSort']);
    $order=array();
    foreach($finalOrder as $o) {
        $order[] = $groupList_Id[$o];
    }
    
    $values['bd_gl_groupSort']=implode(',',$order);

	$form->saveAsSettings($values);
	return TRUE;
}

return $form;