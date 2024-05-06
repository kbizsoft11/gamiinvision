<?php
/**
 * @brief		File Storage Extension: profileBackground
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Profile Backgrounds
 * @since		14 Sep 2021
 */

namespace IPS\profilebackgrounds\extensions\core\FileStorage;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * File Storage Extension: profileBackground
 */
class _profileBackground
{
	/**
	 * Count stored files
	 *
	 * @return	int
	 */
	public function count()
	{
		return \IPS\Db::i()->select( 'COUNT(*)', 'profilebackgrounds_data', 'pb_location IS NOT NULL' )->first();
	}
	
	/**
	 * Move stored files
	 *
	 * @param	int			$offset					This will be sent starting with 0, increasing to get all files stored by this extension
	 * @param	int			$storageConfiguration	New storage configuration ID
	 * @param	int|NULL	$oldConfiguration		Old storage configuration ID
	 * @throws	\UnderflowException					When file record doesn't exist. Indicating there are no more files to move
	 * @return	void|int							An offset integer to use on the next cycle, or nothing
	 */
	public function move( $offset, $storageConfiguration, $oldConfiguration=NULL )
	{
		$record = \IPS\Db::i()->select( '*', 'profilebackgrounds_data', array(), 'pb_member_id', array( $offset, 1 ) )->first();

		try
		{
			$file = \IPS\File::get( $oldConfiguration ?: 'profilebackgrounds_profileBackground', $record['pb_location'] )->move( $storageConfiguration );

			if ( (string) $file != $record['pb_location'] )
			{
				\IPS\Db::i()->update( 'profilebackgrounds_data', array( 'pb_location' => (string) $file ), array( 'pb_member_id=?', $record['pb_member_id'] ) );
			}
		}
		catch( \Exception $e )
		{
			/* Any issues are logged */
		}
	}

	/**
	 * Fix all URLs
	 *
	 * @param	int			$offset					This will be sent starting with 0, increasing to get all files stored by this extension
	 * @return void
	 */
	public function fixUrls( $offset )
	{
		$record = \IPS\Db::i()->select( '*', 'profilebackgrounds_data', array(), 'pb_member_id', array( $offset, 1 ) )->first();

		if ( $new = \IPS\File::repairUrl( $record['pb_location'] ) )
		{
			\IPS\Db::i()->update( 'profilebackgrounds_data', array( 'pb_location' => $new ), array( 'pb_member_id=?', $record['pb_member_id'] ) );
		}
	}

	/**
	 * Check if a file is valid
	 *
	 * @param	string	$file		The file path to check
	 * @return	bool
	 */
	public function isValidFile( $file )
	{
		try
		{
			\IPS\Db::i()->select( '*', 'profilebackgrounds_data', array( 'pb_location=?', (string) $file ) )->first();

			return TRUE;
		}
		catch ( \UnderflowException $e )
		{
			return FALSE;
		}
	}

	/**
	 * Delete all stored files
	 *
	 * @return	void
	 */
	public function delete()
	{
		foreach( \IPS\Db::i()->select( '*', 'profilebackgrounds_data', "pb_location IS NOT NULL" ) as $link )
		{
			try
			{
				\IPS\File::get( 'profilebackgrounds_profileBackground', $link['pb_location'] )->delete();
			}
			catch( \Exception $e ){}
		}
	}
}