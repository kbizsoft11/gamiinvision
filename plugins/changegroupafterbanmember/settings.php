//<?php

$form->addTab( 'cgabmTabBan' );
$form->add( new \IPS\Helpers\Form\YesNo( 'cgabmEnable', \IPS\Settings::i()->cgabmEnable, FALSE, array( 'togglesOn' => array( 'cgabmGroup' ) ) ) );
$form->add( new \IPS\Helpers\Form\Select( 'cgabmGroup', \IPS\Settings::i()->cgabmGroup, FALSE, array( 'options' => \IPS\Member\Group::groups( FALSE, FALSE ), 'parse' => 'normal' ), NULL, NULL, NULL, 'cgabmGroup' ) );

$form->addTab( 'cgabmTabModPost' );
$form->add( new \IPS\Helpers\Form\Node( 'cgabmForums', \IPS\Settings::i()->cgabmForums ? \IPS\Settings::i()->cgabmForums : 0, FALSE, array( 'class' => '\IPS\forums\Forum', 'permissionCheck' => 'view', 'multiple'=> true, 'clubs' => \IPS\Settings::i()->club_nodes_in_apps ) ) );

$form->addTab( 'cgabmProfileBlockTab' );
$form->addMessage( 'cgabmProfileBlockMsg', 'ipsMessage ipsMessage_warning' );
$form->add( new \IPS\Helpers\Form\Enum( 'cgabmProfileBlock', \IPS\Settings::i()->cgabmProfileBlock, FALSE, array( 'options' => \IPS\Member\Group::groups(), 'parse' => 'normal' ), NULL, NULL, NULL, 'cgabmProfileBlock' ) );

if( $values = $form->values() )
{
	$form->saveAsSettings();
	return TRUE;
}

return $form;