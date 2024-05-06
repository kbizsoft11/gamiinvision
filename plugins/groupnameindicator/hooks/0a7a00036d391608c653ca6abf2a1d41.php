//<?php

class hook2 extends _HOOK_CLASS_
{
	public function groupNameIndicator()
	{
		try
		{
				$groups = array();
				$selectedGroups = explode( ',', \IPS\Settings::i()->gni_groups );
		
				if ( \count( $selectedGroups ) )
				{
					foreach( $selectedGroups as $gId )
					{
						if( \array_key_exists( $gId, \IPS\Member\Group::groups() ) )
						{
							$groups[] = \IPS\Member\Group::load( $gId );
						}
					}
				}
	
	
				return $groups;
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