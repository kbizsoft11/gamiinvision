<?php

namespace IPS\tpp;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

class _Log extends \IPS\Patterns\ActiveRecord
{
    protected static $multitons;
	public static $databaseTable = 'tpp_logs';
	public static $databasePrefix = '';
	public static $databaseColumnId = 'id';
    protected static $databaseIdFields = array( 'id', 'topic_id' );
}