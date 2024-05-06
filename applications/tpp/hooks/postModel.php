//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class tpp_hook_postModel extends _HOOK_CLASS_
{
	public static function searchResultSnippet( array $indexData, array $authorData, array $itemData, ?array $containerData, array $reputationData, $reviewRating, $view )
	{
		try
		{
			if( $indexData['index_class'] == 'IPS\forums\Topic\Post' AND ( \IPS\Settings::i()->tpp_app_forums == 0 OR \in_array( $indexData['index_container_id'], explode( ',', \IPS\Settings::i()->tpp_app_forums ) ) ) AND !\IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->tpp_app_groups_exempt ) ) AND $view == 'expanded' )
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( $itemData['tid'] );
				if( $topic->getPassword() AND $topic->author()->member_id != \IPS\Member::loggedIn()->member_id )
				{
					$checkAccess = $topic->canViewPostContent( $topic );
					if( !$checkAccess AND $view == 'expanded' )
					{
						return \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' )->tpp_noAccess();
					}
				}
			}
	
			return parent::searchResultSnippet( $indexData, $authorData, $itemData, $containerData, $reputationData, $reviewRating, $view );
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

	public function content()
	{
		try
		{
			if( $this->item()->getPassword() AND $this->item()->author()->member_id != \IPS\Member::loggedIn()->member_id )
			{
				$checkAccess = $this->item()->canViewPostContent( $this->item() );
				if( !$checkAccess )
				{
					return \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' )->tpp_noAccess();
				}
			}
	
			return $this->mapped('content');
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