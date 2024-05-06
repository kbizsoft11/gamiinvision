//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class tpp_hook_topicController extends _HOOK_CLASS_
{
	protected function manage()
	{
		try
		{
			if( \IPS\Member::loggedIn()->inGroup( explode( ',', \IPS\Settings::i()->tpp_app_groups_exempt ) ) )
			{
				return parent::manage();
			}
	
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch ( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - NO PERMISSION/1', 404, '' );
			}
	
			if( $topic->author()->member_id == \IPS\Member::loggedIn()->member_id )
			{
				return parent::manage();
			}
	
			if( !$topic->checkPasswordSettings() )
			{
				return parent::manage();
			}
	
			if( !\IPS\Member::loggedIn()->member_id AND \IPS\Settings::i()->tpp_forcelogin )
			{
				\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=core&module=system&controller=login', 'front', 'login' ) );
			}
	
			if( isset( \IPS\Request::i()->cookie[ 'ipbtopicpass_' . $topic->tid ] ) AND md5( $topic->getPassword() ) === \IPS\Request::i()->cookie[ 'ipbtopicpass_' . $topic->tid ] )
			{
				return parent::manage();
			}
	
			if( !\IPS\Request::i()->isAjax() )
			{
				\IPS\Output::i()->redirect( $topic->url()->setQueryString( 'do', 'password' ) );
			}
	
			if( \IPS\Request::i()->isAjax() AND \IPS\Request::i()->preview )
			{
				\IPS\Output::i()->sendOutput( \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' )->tpp_noAccess() );
	
				return;
			}
	
			parent::manage();
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

	protected function password()
	{
		try
		{
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch ( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION/1', 404, '' );
			}
	
			if( $topic->author()->member_id == \IPS\Member::loggedIn()->member_id )
			{
				\IPS\Output::i()->redirect( $topic->url() );
			}
	
			$password 	= $topic->getPassword();
			$forum 		= $topic->container();
		
			$form = new \IPS\Helpers\Form( 'form', 'proceed' );
			$form->class = 'ipsForm_vertical ipsForm_noLabels';
			$form->add( new \IPS\Helpers\Form\Password( 'password', NULL, FALSE, array( 'minLength' => \IPS\Settings::i()->tpp_app_min_password, 'maxLength' => \IPS\Settings::i()->tpp_app_max_password ), function( $val ) use ( $password )
			{
				if( $val != $password )
				{
					throw new \DomainException( 'tpp_password_error' );
				}
			} ) );
		
			if( $values = $form->values() )
			{
				\IPS\Request::i()->setCookie( 'ipbtopicpass_' . $topic->tid, md5( $password ), \IPS\DateTime::create()->add( new \DateInterval( 'P'. \IPS\Settings::i()->tpp_app_cookie_days . 'D' ) ) );
				\IPS\Output::i()->redirect( $topic->url() );
			}
	
			foreach( $forum->parents() AS $parent )
			{
				\IPS\Output::i()->breadcrumb[] = array( $parent->url(), $parent->_title );
			}
	
			\IPS\Output::i()->breadcrumb[] = array( $forum->url(), $forum->_title );
			\IPS\Output::i()->breadcrumb[] = array( $topic->url(), $topic->mapped('title') );
			\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack( 'tpp_enter_topic_password' ) );
			\IPS\Output::i()->title  = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_enter_topic_password' ) . ' - ' . $topic->mapped('title');
			\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' )->tpp_password( $form );
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

	protected function addPassword()
	{
		try
		{
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - ADD PASSWORD/1', 404, '' );
			}
	
			if( !$topic->canManagePasswords() )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - ADD PASSWORD/2', 404, '' );
			}
	
			$form = new \IPS\Helpers\Form( 'form', 'save' );
			$form->class = 'ipsPad';
			$form->add( new \IPS\Helpers\Form\Password( 'password', NULL, FALSE, array( 'minLength' => \IPS\Settings::i()->tpp_app_min_password, 'maxLength' => \IPS\Settings::i()->tpp_app_max_password ) ) );
	
			if ( $values = $form->values() )
			{
				$topic->addPassword( $values['password'] );
	
				if( $topic->starter_id != \IPS\Member::loggedIn()->member_id )
				{
					\IPS\Request::i()->setCookie( 'ipbtopicpass_' . $topic->tid, md5( $topic->password ), \IPS\DateTime::create()->add( new \DateInterval( 'P'. \IPS\Settings::i()->tpp_cookie_days . 'D' ) ) );
				}
	
				if( \IPS\Settings::i()->tpp_app_log )
				{
					$action = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_app_log_password_added' );
					\IPS\Member::loggedIn()->language()->parseOutputForDisplay( $action );
	
					$log = new \IPS\tpp\Log;
	
					$log->topic_id  	= $topic->tid;
					$log->member_id 	= \IPS\Member::loggedIn()->member_id;
					$log->ip_address 	= \IPS\Request::i()->ipAddress();
					$log->action 		= $action;
					$log->date 			= time();
					$log->save();
				}
	
				\IPS\Output::i()->redirect( $topic->url(), 'tpp_app_password_added' );
			}
	
			if( !\IPS\Request::i()->isAjax() )
			{
				\IPS\Output::i()->breadcrumb[] = array( $topic->container()->url(), $topic->container()->_title );
				\IPS\Output::i()->breadcrumb[] = array( $topic->url(), $topic->mapped('title') );
				\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack( 'tpp_add_password' ) );
				\IPS\Output::i()->title  = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_add_password' ) . ' - ' . $topic->mapped('title');
			}
	
			\IPS\Output::i()->output = $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
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

	protected function editPassword()
	{
		try
		{
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - CHANGE PASSWORD/1', 404, '' );
			}
	
			if( !$topic->canManagePasswords() )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - CHANGE PASSWORD/2', 404, '' );
			}
	
			$form = new \IPS\Helpers\Form( 'form', 'tpp_edit_password' );
			$form->class = 'ipsPad';
			$form->add( new \IPS\Helpers\Form\Password( 'password', $topic->password, FALSE, array( 'minLength' => \IPS\Settings::i()->tpp_app_min_password, 'maxLength' => \IPS\Settings::i()->tpp_app_max_password ) ) );
	
			if( $values = $form->values() )
			{
				$topic->editPassword( $topic->getPasswordMetaDataId(), $values['password'] );
	
				if( $topic->starter_id != \IPS\Member::loggedIn()->member_id )
				{
					\IPS\Request::i()->setCookie( 'ipbtopicpass_' . $topic->tid, md5( $topic->password ), \IPS\DateTime::create()->add( new \DateInterval( 'P'. \IPS\Settings::i()->tpp_cookie_days . 'D' ) ) );
				}
	
				if( \IPS\Settings::i()->tpp_app_log )
				{
					$action = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_app_log_password_edited' );
					\IPS\Member::loggedIn()->language()->parseOutputForDisplay( $action );
	
					$log = new \IPS\tpp\Log;
	
					$log->topic_id  	= $topic->tid;
					$log->member_id 	= \IPS\Member::loggedIn()->member_id;
					$log->ip_address 	= \IPS\Request::i()->ipAddress();
					$log->action 		= $action;
					$log->date 			= time();
	
					$log->save();
				}
			
				\IPS\Output::i()->redirect( $topic->url(), 'tpp_app_password_edited' );
			}
	
			if( !\IPS\Request::i()->isAjax() )
			{
				\IPS\Output::i()->breadcrumb[] = array( $topic->container()->url(), $topic->container()->_title );
				\IPS\Output::i()->breadcrumb[] = array( $topic->url(), $topic->mapped('title') );
				\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack( 'tpp_edit_password' ) );
				\IPS\Output::i()->title  = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_edit_password' ) . ' - ' . $topic->mapped('title');
			}
		
			\IPS\Output::i()->output = $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
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

	protected function delPassword()
	{
		try
		{
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - REMOVE PASSWORD/1', 404, '' );
			}
	
			if( !$topic->canManagePasswords() )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - REMOVE PASSWORD/2', 404, '' );
			}
	
			$topic->deletePassword( $topic->getPasswordMetaDataId() );
	
			\IPS\Request::i()->setCookie( 'ipbtopicpass_' . $topic->tid, NULL );
	
			if( \IPS\Settings::i()->tpp_app_log )
			{
				$action = \IPS\Member::loggedIn()->language()->addToStack( 'tpp_app_log_password_deleted' );
				\IPS\Member::loggedIn()->language()->parseOutputForDisplay( $action );
	
				$log = new \IPS\tpp\Log;
	
				$log->topic_id  	= $topic->tid;
				$log->member_id 	= \IPS\Member::loggedIn()->member_id;
				$log->ip_address 	= \IPS\Request::i()->ipAddress();
				$log->action 		= $action;
				$log->date 			= time();
	
				$log->save();
			}
	
			\IPS\Output::i()->redirect( $topic->url(), 'tpp_app_password_removed' );
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

	protected function passwordLogs()
	{
		try
		{
			if( !\IPS\Member::loggedIn()->modPermission('tpp_view_logs') )
			{
				\IPS\Output::i()->error( 'tpp_no_permission', 'TOPIC PASSWORD PROTECTION - VIEW LOG/1', 403, 'tpp_no_permission' );
			}
	
			try
			{
				$topic = \IPS\forums\Topic::loadAndCheckPerms( \IPS\Request::i()->id );
			}
			catch ( \OutOfRangeException $e )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - VIEW LOG/2', 404, '' );
			}
	
			if( !$topic->canManagePasswords() )
			{
				\IPS\Output::i()->error( 'node_error', 'TOPIC PASSWORD PROTECTION - VIEW LOG/3', 404, '' );
			}
			
			$table = new \IPS\Helpers\Table\Db( 'tpp_logs', $topic->url()->setQueryString( 'do', 'passwordLogs' ), array( array( 'topic_id=?', $topic->tid ) ) );
			$table->joins = array(
				array( 'select' => 'm.*', 'from' => array( 'core_members', 'm' ), 'where' => "m.member_id=tpp_logs.member_id" )
			);
	
			$table->sortBy 			= 'date';
			$table->sortDirection 	= 'desc';
	
			$table->tableTemplate 	= array( \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' ), 'tpp_passwordTable' );
			$table->rowsTemplate 	= array( \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' ), 'tpp_passwordRows' );
			$table->limit			= 10;
	
			\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'topic', 'tpp', 'front' )->tpp_passwordGeneral(  (string) $table );
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