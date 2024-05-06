<?php
/**
 * @brief		Member Sync
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community

 * @since		21 Aug 2023
 */

namespace IPS\core\extensions\core\MemberSync;
use IPS\core\Hive;
use IPS\Db;
use IPS\Member;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Sync
 */
class _Hive
{
	/**
	 * Member account has been updated
	 *
	 * @param	$member		Member	Member updating profile
	 * @param	$changes	array		The changes
	 * @return	void
	 */
	public function onProfileUpdate( $member, $changes )
	{
		if( \in_array( [ 'member_group_id', 'mgroup_others', 'temp_ban' ], array_keys( $changes ) ) )
		{
			$this->_updateHive( $member, 'update' );
		}
	}
	
	/**
	 * Member is merged with another member
	 *
	 * @param	Member	$member		Member being kept
	 * @param	Member	$member2	Member being removed
	 * @return	void
	 */
	public function onMerge( $member, $member2 )
	{
		$this->_updateHive( $member2, 'merge', $member->member_id );
		try
		{
			Db::i()->insert( 'core_hive_subscribers', [ 'member_id' => $member->member_id, 'subscribe_date' => time() ] );
		}
		catch( Db\Exception $e ) {}
		Db::i()->delete( 'core_hive_subscribers', [ 'member_id=?', $member2->member_id ] );
	}
	
	/**
	 * Member is deleted
	 *
	 * @param	$member	Member	The member
	 * @return	void
	 */
	public function onDelete( $member )
	{
		$this->_updateHive( $member, 'delete' );
		Db::i()->delete( 'core_hive_subscribers', [ 'member_id=?', $member->member_id ] );
	}

	/**
	 * Update HIVE API
	 *
	 * @param   Member      $member
	 * @param   string      $type
	 * @param   int|null    $newMergeId
	 * @return  void
	 */
	protected function _updateHive( Member $member, string $type, int $newMergeId=null )
	{
		/* Check to see if we might be subscribed before pushing this change */
		try
		{
			Db::i()->select( '*', 'core_hive_subscribers', [ 'member_id=?', $member->member_id ] )->first();
		}
		catch( \UnderflowException $e )
		{
			return;
		}

		$data = [
			'site_member_id' => $member->member_id,
			'group_hash' => $type == 'delete' ? 'guest' : Hive::groupHash( $member ),
		];

		if( $type === 'delete' )
		{
			$data['new_site_member_id'] = 0;
		}
		elseif( $type === 'merge' AND $newMergeId )
		{
			$data['new_site_member_id'] = $newMergeId;
		}

		try
		{
			Hive::api( 'groupupdate',  $data );
		}
		catch( \Exception | \Throwable | \LogicException $e ) { }
	}
}