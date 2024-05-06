//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class profilebackgrounds_hook_profileController extends _HOOK_CLASS_
{
	protected function profileBackgroundImage()
	{
		try
		{
			if( !( \IPS\Member::loggedIn()->modPermission('can_modify_profiles') OR \IPS\Member::loggedIn()->member_id === $this->member->member_id AND ( \IPS\Settings::i()->profilebackground_Groups == '*' OR \IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->profilebackground_Groups ) ) ) ) )
			{
				\IPS\Output::i()->error( 'no_permission_edit_profile', 'PROFILE BACKGROUNDS/1', 403, '' );
			}
	
			$form = new \IPS\Helpers\Form;
			$form->addTab( 'profile_edit_basic_tab', 'user');
			$form->add( new \IPS\Helpers\Form\Upload( 'ProfileBackgroundImage_Upload', $this->member->profileBackgroundImage() ? \IPS\File::get( 'profilebackgrounds_profileBackground', $this->member->profileBackgroundImage() ) : NULL, FALSE, array( 'image' => true, 'storageExtension' => 'profilebackgrounds_profileBackground' ), NULL, NULL, NULL, "ProfileBackgroundImage_Upload" ) );
	
			if( $values = $form->values() )
			{
				\IPS\Db::i()->replace( 'profilebackgrounds_data', array( 'pb_member_id' => $this->member->member_id, 'pb_location' => $values['ProfileBackgroundImage_Upload'] ) );
				\IPS\Output::i()->redirect( $this->member->url(), 'saved' );
			}
	
			\IPS\Output::i()->output = $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
	
			\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack( 'editing_profile', FALSE, array( 'sprintf' => array( $this->member->name ) ) );
			\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack( 'editing_profile', FALSE, array( 'sprintf' => array( $this->member->name ) ) ) );
		}
		catch ( \Error | \RuntimeException $e )
		{
			if ( method_exists( get_parent_class(), __FUNCTION__ ) )
			{
				return \call_user_func_array( 'parent::' . __FUNCTION__, \func_get_args() );
			}
			else
			{
				throw $e;
			}
		}
	}
}