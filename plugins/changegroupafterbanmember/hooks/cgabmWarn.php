//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook92 extends _HOOK_CLASS_
{
	/**
	 * Create from form
	 *
	 * @param	array					$values				Values from form
	 * @param	\IPS\Node\Model|NULL	$container			Container (e.g. forum), if appropriate
	 * @param	bool					$sendNotification	Send Notification
	 * @return	\IPS\cms\Records
	 */
	public static function createFromForm( $values, \IPS\Node\Model $container = NULL, $sendNotification = TRUE )
	{
		try
		{
			$warn 	= parent::createFromForm( $values, $container, $sendNotification );
			$warned = \IPS\Member::load( $warn->member );
	
			if( \IPS\Settings::i()->cgabmEnable AND \IPS\Settings::i()->cgabmGroup AND $warn->suspend == -1 )
			{
				$warned->member_group_id = \IPS\Settings::i()->cgabmGroup;
				$warned->save();
			}
	
			return $warn;
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