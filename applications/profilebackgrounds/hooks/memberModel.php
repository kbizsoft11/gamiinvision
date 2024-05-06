//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class profilebackgrounds_hook_memberModel extends _HOOK_CLASS_
{
	public function profileBackgroundImage()
	{
		try
		{
			try
			{
				return \IPS\Db::i()->select( 'pb_location', 'profilebackgrounds_data', array( 'pb_location!="" AND pb_member_id=?', $this->member_id ) )->first();
			}
			catch( \Exception $ex )
			{
				return FALSE;
			}
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