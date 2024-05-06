<?php


namespace IPS\profilebackgrounds\modules\admin\settings;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	protected function manage()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'settings_manage' );

		$form = new \IPS\Helpers\Form;
		$form->add( new \IPS\Helpers\Form\CheckboxSet( 'profilebackground_Groups', !\IPS\Settings::i()->profilebackground_Groups ? 0 : ( \IPS\Settings::i()->profilebackground_Groups == '*' ? '*' : explode( ',', \IPS\Settings::i()->profilebackground_Groups ) ), FALSE, array( 'options' => \IPS\Member\Group::groups( TRUE, FALSE ), 'parse' => 'normal', 'unlimited' => '*' ) ) );

		if( $values = $form->values( TRUE ) )
		{
			$form->saveAsSettings( $values );
			\IPS\Session::i()->log( 'acplogs__profilebackgrounds_settings' );
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack( 'settings' );
		\IPS\Output::i()->output = $form;
	}
}