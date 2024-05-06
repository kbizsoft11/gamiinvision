<?php
/**
 * @brief		Member Sync
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Profile Backgrounds
 * @since		14 Sep 2021
 */

namespace IPS\profilebackgrounds\extensions\core\MemberSync;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Sync
 */
class _profileBackground
{
	/**
	 * Member is merged with another member
	 *
	 * @param	\IPS\Member	$member		Member being kept
	 * @param	\IPS\Member	$member2	Member being removed
	 * @return	void
	 */
	public function onMerge( $member, $member2 )
	{
		\IPS\Db::i()->update( 'profilebackgrounds_data', array( 'pb_member_id' => $member->member_id ), array( 'pb_member_id=?', $member2->member_id ), array(), NULL, \IPS\Db::IGNORE );
	}

	/**
	 * Member is deleted
	 *
	 * @param	$member	\IPS\Member	The member
	 * @return	void
	 */
	public function onDelete( $member )
	{
		try
		{
			$data = \IPS\Db::i()->select( '*', 'profilebackgrounds_data', array( 'pb_member_id=?', $member->member_id ) )->first();
			\IPS\File::get( 'profilebackgrounds_profileBackground', $data['pb_location'] )->delete();
		}
		catch( \UnderflowException $e ){}

		\IPS\Db::i()->delete( 'profilebackgrounds_data', array( 'pb_member_id=?', $member->member_id ) );
	}
}