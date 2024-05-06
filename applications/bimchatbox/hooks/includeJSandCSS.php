//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class bimchatbox_hook_includeJSandCSS extends _HOOK_CLASS_
{

	protected static function baseJs()
	{
		try
		{
			parent::baseJs();
			if ( ! \IPS\Request::i()->isAjax() && \IPS\Dispatcher::i()->controllerLocation != 'admin' )
			{
				\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'front_chatbox.js', 'bimchatbox', 'front' ) );
			}
		}
		catch ( \Error | \RuntimeException $e )
		{
			if( \defined( '\IPS\DEBUG_HOOKS' ) AND \IPS\DEBUG_HOOKS )
			{
				\IPS\Log::log( $e, 'hook_exception' );
			}

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
	
	public static function baseCss()
	{
		try
		{
			parent::baseCss();
			if ( ! \IPS\Request::i()->isAjax() && \IPS\Dispatcher::i()->controllerLocation != 'admin' )
			{
				\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'chatbox.css', 'bimchatbox', 'front' ) );
			}
		}
		catch ( \Error | \RuntimeException $e )
		{
			if( \defined( '\IPS\DEBUG_HOOKS' ) AND \IPS\DEBUG_HOOKS )
			{
				\IPS\Log::log( $e, 'hook_exception' );
			}

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
