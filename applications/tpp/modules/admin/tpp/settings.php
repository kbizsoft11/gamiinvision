<?php

namespace IPS\tpp\modules\admin\tpp;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

class _settings extends \IPS\Dispatcher\Controller
{
	protected function manage()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'settings_manage' );
		$form = new \IPS\Helpers\Form;

		$form->addTab('tpp_app_tab_basic');
		$form->add( new \IPS\Helpers\Form\CheckboxSet( 'tpp_app_groups', explode( ',', \IPS\Settings::i()->tpp_app_groups ), FALSE, array( 'options' => \IPS\Member\Group::groups( TRUE, FALSE ), 'multiple' => TRUE, 'parse' => 'normal' ) ) );
		$form->add( new \IPS\Helpers\Form\CheckboxSet( 'tpp_app_groups_exempt', explode( ',', \IPS\Settings::i()->tpp_app_groups_exempt ), FALSE, array( 'options' => \IPS\Member\Group::groups( TRUE, FALSE ), 'multiple' => TRUE, 'parse' => 'normal' ) ) );

		$form->add( new \IPS\Helpers\Form\Node( 'tpp_app_forums', \IPS\Settings::i()->tpp_app_forums ? \IPS\Settings::i()->tpp_app_forums : 0, FALSE, array(
					'class'           	=> '\IPS\forums\Forum',
					'zeroVal'         	=> 'tpp_app_all_forums',
					'permissionCheck' 	=> 'view',
					'multiple'        	=> true,
					'clubs'				=> \IPS\Settings::i()->club_nodes_in_apps
		) ) );

		$form->addTab('tpp_app_tab_advanced');
		$form->add( new \IPS\Helpers\Form\YesNo( 'tpp_app_log', \IPS\Settings::i()->tpp_app_log, FALSE ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'tpp_app_forcelogin', \IPS\Settings::i()->tpp_app_forcelogin, FALSE ) );
		$form->add( new \IPS\Helpers\Form\Number( 'tpp_app_cookie_days', \IPS\Settings::i()->tpp_app_cookie_days, FALSE, array( 'min' => 1, 'max' => 30 ), NULL, NULL, \IPS\Member::loggedIn()->language()->addToStack( 'days' ), 'tpp_app_cookie_days' ) );
		$form->add( new \IPS\Helpers\Form\Number( 'tpp_app_min_password', \IPS\Settings::i()->tpp_app_min_password, FALSE, array( 'min' => 3, 'max' => 32 ), NULL, NULL, \IPS\Member::loggedIn()->language()->addToStack( 'characters_lc' ), 'tpp_app_min_password' ) );
		$form->add( new \IPS\Helpers\Form\Number( 'tpp_app_max_password', \IPS\Settings::i()->tpp_app_max_password, FALSE, array( 'min' => 5, 'max' => 32 ), NULL, NULL, \IPS\Member::loggedIn()->language()->addToStack( 'characters_lc' ), 'tpp_app_max_password' ) );

		if( $values = $form->values( TRUE ) )
		{
			$form->saveAsSettings( $values );
			\IPS\Session::i()->log( 'acplogs__tpp_settings' );
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('settings');
		\IPS\Output::i()->output = $form;
	}
}