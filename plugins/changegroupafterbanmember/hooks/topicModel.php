//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook91 extends _HOOK_CLASS_
{
	/**
	 * Can Comment/Review
	 *
	 * @param	string				$type	Type
	 * @param	\IPS\Member\NULL	$member	The member (NULL for currently logged in member)
	 * @return	bool
	 */
	protected function canCommentReview( $type, \IPS\Member $member = NULL, $considerPostBeforeRegistering = TRUE )
	{
		try
		{
			$member = $member ?: \IPS\Member::loggedIn();
	
			if( $member->member_id AND $member->mod_posts AND \in_array( $this->container()->id, explode( ',', \IPS\Settings::i()->cgabmForums ) ) )
			{
				return FALSE;
			}
	
			return parent::canCommentReview( $type, $member, $considerPostBeforeRegistering );
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