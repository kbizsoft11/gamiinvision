//<?php


$form->add( new \IPS\Helpers\Form\Stack( 'gni_groups', (isset(\IPS\Settings::i()->gni_groups)) ? explode(",", \IPS\Settings::i()->gni_groups) : NULL, TRUE, array( 'stackFieldType' => 'Select', 'options' => \IPS\Member\Group::groups(TRUE, FALSE), 'parse' => 'normal' ) ) );

$form->add(	new \IPS\Helpers\Form\YesNo( 'gni_recently_browsing', \IPS\Settings::i()->gni_recently_browsing, FALSE  ));
$form->add(	new \IPS\Helpers\Form\YesNo( 'gni_whos_online', \IPS\Settings::i()->gni_whos_online, FALSE  ));


if ( $values = $form->values() )
{
    // \IPS\Session::i()->csrfCheck();
	$form->saveAsSettings();
	return TRUE;
}

return $form;