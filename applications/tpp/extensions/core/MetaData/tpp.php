<?php
/**
 * @brief		Meta Data: tpp
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Topic Password Protection
 * @since		31 May 2022
 */

namespace IPS\tpp\extensions\core\MetaData;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * @brief	Meta Data: tpp
 */
class _tpp
{
	public function addPassword( \IPS\Content\Item $item, $value=NULL )
	{
		$id = $item->addMeta( 'tpp_tpp', array( 'password' => $value ) );
		return $id;
	}

	public function editPassword( \IPS\Content\Item $item, $id, $value )
	{
		$item->editMeta( $id, array( 'password' => $value ) );
	}

	public function deletePassword( $id, \IPS\Content\Item $item )
	{
		$item->deleteMeta( $id );
	}
}