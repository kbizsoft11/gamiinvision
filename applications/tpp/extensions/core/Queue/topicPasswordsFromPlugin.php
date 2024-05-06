<?php
/**
 * @brief		Background Task
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Topic Password Protection
 * @since		03 Jun 2022
 */

namespace IPS\tpp\extensions\core\Queue;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Background Task
 */
class _topicPasswordsFromPlugin
{
	/**
	 * @brief Number of members to run per cycle
	 */
	public $rebuild	= \IPS\REBUILD_QUICK;

	/**
	 * Parse data before queuing
	 *
	 * @param	array	$data
	 * @return	array
	 */
	public function preQueueData( $data )
	{
		try
		{
			$data['count'] = (int) \IPS\Db::i()->select( 'COUNT(*)', 'forums_topics', array( 'password<>""' ) )->first();
		}
		catch( \Exception $ex )
		{
			throw new \OutOfRangeException;
		}

		if( $data['count'] == 0 )
		{
			return null;
		}

		return $data;
	}

	/**
	 * Run Background Task
	 *
	 * @param	mixed						$data	Data as it was passed to \IPS\Task::queue()
	 * @param	int							$offset	Offset
	 * @return	int							New offset
	 * @throws	\IPS\Task\Queue\OutOfRangeException	Indicates offset doesn't exist and thus task is complete
	 */
	public function run( $data, $offset )
	{
		$select = \IPS\Db::i()->select( 'tid, password', 'forums_topics', array( 'password<>""' ), 'tid ASC', array( $offset, $this->rebuild ) );

		if( !$select->count() )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		foreach( $select as $row )
		{
			$topic = \IPS\forums\Topic::load( $row['tid'] );
			$topic->addPassword( $row['password'] );
		}

		return $offset + $this->rebuild;
	}
	
	/**
	 * Get Progress
	 *
	 * @param	mixed					$data	Data as it was passed to \IPS\Task::queue()
	 * @param	int						$offset	Offset
	 * @return	array( 'text' => 'Doing something...', 'complete' => 50 )	Text explaining task and percentage complete
	 * @throws	\OutOfRangeException	Indicates offset doesn't exist and thus task is complete
	 */
	public function getProgress( $data, $offset )
	{
		return array( 'text' => \IPS\Member::loggedIn()->language()->addToStack( 'tpp_rebuilding_topics_password' ), 'complete' => $data['count'] ? ( round( 100 / $data['count'] * $offset, 2 ) ) : 100 );
	}

	/**
	 * Perform post-completion processing
	 *
	 * @param	array	$data		Data returned from preQueueData
	 * @param	bool	$processed	Was anything processed or not? If preQueueData returns NULL, this will be FALSE.
	 * @return	void
	 */
	public function postComplete( $data, $processed = TRUE )
	{
		try
		{
			$pluginData = \IPS\Db::i()->select( '*', 'core_plugins', array( 'plugin_location=?', "topicpasswordprotection" ) )->first();
			try
			{
				$plugin = \IPS\Plugin::load( $pluginData['plugin_id'] );
				$plugin->delete();
			}
			catch ( \Exception $e ){}
		}
		catch( \UnderflowException $e ){}
	}
}