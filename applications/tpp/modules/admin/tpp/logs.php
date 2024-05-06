<?php

namespace IPS\tpp\modules\admin\tpp;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

class _logs extends \IPS\Dispatcher\Controller
{	
	protected function manage()
	{		
		\IPS\Dispatcher::i()->checkAcpPermission( 'logs_manage' );

		$table = new \IPS\Helpers\Table\Db( 'tpp_logs', \IPS\Http\Url::internal( 'app=tpp&module=tpp&controller=logs' ) );
		$table->joins = array(
			array( 'select' => 'm.*', 'from' => array( 'core_members', 'm' ), 'where' => "m.member_id=tpp_logs.member_id" )
		);

		$table->sortBy 			= 'date';
		$table->sortDirection 	= 'desc';

		$table->tableTemplate 	= array( \IPS\Theme::i()->getTemplate( 'logs', 'tpp', 'admin' ), 'tpp_passwordTable' );
		$table->rowsTemplate 	= array( \IPS\Theme::i()->getTemplate( 'logs', 'tpp', 'admin' ), 'tpp_passwordRows' );
		$table->limit			= 10;

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('tpp_logs_password');
		\IPS\Output::i()->output	= \IPS\Theme::i()->getTemplate( 'global', 'core' )->block( 'title', (string) $table );
	}
}