//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class tpp_hook_topicModel extends _HOOK_CLASS_
{
	public static function supportedMetaDataTypes()
	{
		try
		{
	
			$types = parent::supportedMetaDataTypes();
	
			array_push( $types, 'tpp_tpp' );
	
			return $types;
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

	public function addPassword( $value )
	{
		try
		{
			return \IPS\Application::load('tpp')->extensions( 'core', 'MetaData' )['tpp']->addPassword( $this, $value );
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

	public function editPassword( $id, $value )
	{
		try
		{
			\IPS\Application::load('tpp')->extensions( 'core', 'MetaData' )['tpp']->editPassword( $this, $id, $value );
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

	public function deletePassword( $id )
	{
		try
		{
			\IPS\Application::load('tpp')->extensions( 'core', 'MetaData' )['tpp']->deletePassword( $id, $this );
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

	public function getPassword()
	{
		try
		{
			$password = "";
			foreach( $this->getMeta() as $k => $v )
			{
				if( $k == "tpp_tpp" )
				{
					$password = current( $v )['password'];
				}
			}	
	
			return $password;
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

	public function getPasswordMetaDataId()
	{
		try
		{
			foreach( $this->getMeta() as $k => $v )
			{
				if( $k == "tpp_tpp" )
				{
					return key( $v );
				}
			}
	
			return 0;
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

	public function canManagePasswords()
	{
		try
		{
			if( !\IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->tpp_app_groups ) ) )
			{
				return FALSE;
			}
	
			if( \IPS\Member::loggedIn()->member_id != $this->author()->member_id )
			{
				return FALSE;
			}
	
			if( \IPS\Settings::i()->tpp_app_forums != 0 AND !\in_array( $this->container()->id, explode( ',', \IPS\Settings::i()->tpp_app_forums ) ) )
			{
				return FALSE;
			}
	
			return TRUE;
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

	public function checkPasswordSettings()
	{
		try
		{
			if( \IPS\Settings::i()->tpp_app_forums != 0 AND !\in_array( $this->container()->id, explode( ',', \IPS\Settings::i()->tpp_app_forums ) ) )
			{
				return FALSE;
			}
	
			if( !$this->getPasswordMetaDataId() )
			{
				return FALSE;
			}
	
			return TRUE;
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

	public function canViewPostContent( $topic )
	{
		try
		{
			if( !$topic->getPassword() )
			{
				return FALSE;
			}
	
			if( \IPS\Settings::i()->tpp_app_forums != '0' )
			{
				if( !\in_array( $topic->container()->id, explode( ',', \IPS\Settings::i()->tpp_app_forums ) ) )
				{
					return FALSE;
				}
			}
	
			if( !\IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->tpp_app_groups_exempt ) ) AND $topic->author()->member_id != \IPS\Member::loggedIn()->member_id AND ( !isset( \IPS\Request::i()->cookie[ 'ipbtopicpass_' . $topic->tid ] ) OR md5( $topic->getPassword() ) !== \IPS\Request::i()->cookie[ 'ipbtopicpass_' . $topic->tid ] ) )
			{
				return FALSE;
			}
	
			return TRUE;
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

	public static function formElements( $item=NULL, \IPS\Node\Model $container=NULL )
	{
		try
		{
			$return = parent::formElements( $item, $container );
	
			if( ( \in_array( $container->id, explode( ',', \IPS\Settings::i()->tpp_app_forums ) ) OR \IPS\Settings::i()->tpp_app_forums == '0' ) AND \IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->tpp_app_groups ) ) AND \IPS\Request::i()->do == 'add' )
			{
				$merge = array();
	
				$merge['password'] = new \IPS\Helpers\Form\Password( 'password', NULL, FALSE, array( 'minLength' => \IPS\Settings::i()->tpp_app_min_password, 'maxLength' => \IPS\Settings::i()->tpp_app_max_password ) );
				$merge['password']->rowClasses[] = 'ipsFieldRow_primary';
	
				array_splice( $return, 1, 0, $merge );
			}
	
			return $return;
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

	public function processForm( $values )
	{
		try
		{
			parent::processForm( $values );
	
			if( isset( $values['password'] ) AND $values['password'] )
			{
				$this->addPassword( $values['password'] );
	
				if( \IPS\Settings::i()->tpp_app_log )
				{
					$action = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_app_log_password_added' );
					\IPS\Member::loggedIn()->language()->parseOutputForDisplay( $action );
	
					$log = new \IPS\tpp\Log;
	
					$log->topic_id  	= $this->tid;
					$log->member_id 	= \IPS\Member::loggedIn()->member_id;
					$log->ip_address 	= \IPS\Request::i()->ipAddress();
					$log->action 		= $action;
					$log->date 			= time();
	
					$log->save();
				}
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