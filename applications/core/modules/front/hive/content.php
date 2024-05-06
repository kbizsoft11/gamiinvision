<?php
/**
 * @brief		content
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community

 * @since		21 Aug 2023
 */

namespace IPS\core\modules\front\hive;

use IPS\core\Hive;
use IPS\Content\Item;
use IPS\core\Stream;
use IPS\Db;
use IPS\File;
use IPS\File\Exception;
use IPS\Http\Url;
use IPS\IPS;
use IPS\Member;
use IPS\Output;
use IPS\Patterns\ActiveRecordIterator;
use IPS\Request;
use IPS\Settings;
use Jose\Component\Checker\AudienceChecker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\InvalidClaimException;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\IssuerChecker;
use Jose\Component\Checker\MissingMandatoryClaimException;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Signature\Serializer\CompactSerializer;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * content
 */
class _content extends \IPS\Dispatcher\Controller
{
	public function __construct( $url = NULL )
	{
		parent::__construct( $url );

		IPS::$PSR0Namespaces['Jose'] = \IPS\ROOT_PATH . '/system/3rd_party/JwtFramework/src';
		IPS::$PSR0Namespaces['Base64Url']  = \IPS\ROOT_PATH .'/system/3rd_party/Base64Url';

		\IPS\Output::i()->pageCaching = FALSE;
	}

	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		if( isset( Request::i()->click ) )
		{
			$this->_doClick();
		}
		elseif( isset( Request::i()->follow ) )
		{
			$this->_doFollowCommunity();
		}
		else
		{
			/* Don't feed any content if disabled */
			if( !\IPS\core\Hive::enabled() )
			{
				Output::i()->json( [ 'error' => 'Community Hive not enabled' ], 404 );
			}

			$this->_verifyJwt();
		}

		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		try
		{
			match ( $this->payload['request_type'] )
			{
				'content' => $this->_getContent(),
				'sync' => $this->_syncMembers(),
				'unfollow' => $this->_unfollow()
			};
		}
		catch( \UnhandledMatchError $e )
		{
			Output::i()->json( array( 'error' => 'Invalid request type' ), 400 );
		}
	}

	/**
	 * Process Hive click and redirect
	 *
	 * @return void
	 */
	protected function _doClick()
	{
		try
		{
			$string = base64_decode( Request::i()->click );
			$result = [];
			parse_str( $string, $result );

			if( empty( $result['key2' ] ) )
			{
				throw new \UnexpectedValueException( 'key2 missing' );
			}

			$item = explode( '/', $result['key2'] );

			if( \count( $item ) !== 2 )
			{
				throw new \UnexpectedValueException( 'key2 format unexpected' );
			}

			if( !class_exists( $item[0], TRUE ) )
			{
				throw new \UnexpectedValueException( 'key2 data unexpected' );
			}

			$object = $item[0]::load( (int) $item[1] );

			if( !( $object instanceof \IPS\Content ) )
			{
				throw new \UnexpectedValueException( 'key2 data unexpected 2' );
			}

			Output::i()->redirect( $object->url() );
		}
		catch( \UnexpectedValueException | \OutOfRangeException $e )
		{
			Output::i()->redirect( Url::internal( '' ) );
		}
	}

	/**
	 * Redirect to Hive follow page
	 *
	 * @return void
	 */
	protected function _doFollowCommunity()
	{
		Output::i()->redirect( Url::internal( 'app=core&module=hive&controller=account&do=follow', 'front', 'hive_follow' ) );
	}

	/**
	 * Get content for Hive
	 *
	 * @return void
	 */
	protected function _getContent()
	{
		/* Make sure we have a valid payload */
		if( ! isset ( $this->payload['group_hash'] ) )
		{
			Output::i()->json( [ 'error' => 'Missing Group Hash' ], 400 );
		}

		/* Use new member object for permissions */
		$member = new Member;

		/* Set secondary groups */
		$member->member_group_id = Settings::i()->guest_group;
		if( $this->payload['group_hash'] !== 'guest' )
		{
			$member->mgroup_others = $this->payload['group_hash'];
		}

		$stream = Stream::allActivityStream();
		$stream->date_relative_days = 365;
		$stream->id					= 0;
		$stream->include_comments	= TRUE;
		$stream->baseUrl			= Url::internal( "app=core&module=discover&controller=streams", 'front', 'discover_all' );

		/* Content Limitations */
		$settings = json_decode( Settings::i()->hive_content, TRUE );
		if( $settings['content_classes'] !== '*' )
		{
			$stream->classes = $settings['content_classes'];
		}
		else
		{
			$stream->classes = '';
			foreach ( \IPS\Content::routedClasses( TRUE, FALSE, TRUE ) as $class )
			{
				if ( is_subclass_of( $class, 'IPS\Content\Searchable' ) and isset( $class::$databaseColumnMap['date'] ) )
				{
					$stream->classes .= ',' . $class;
				}
			}
		}

		/* Container - Stream wants JSON and we have an array... */
		$stream->containers = json_encode( $settings['content_containers'] );

		$query		= $stream->query( $member )->setLimit( 10 );
		$results	= $query->search();

		$return = [];
		foreach( $results as $comment )
		{
			$commentData = $comment->asArray();
			$commentClass = $commentData['indexData']['index_class'];
			$itemClass = $commentClass::$itemClass ?? $commentClass;
			$item = $itemClass::load( $commentData['indexData']['index_item_id'] );

			/* Attachments  */
			$attachment = $fileObj = NULL;
			try
			{
				if( !empty( $commentData['itemData']['attachedImages'] ) )
				{
					$fileObj = File::get( $commentData['itemData']['attachedImages'][0]['extension'], $commentData['itemData']['attachedImages'][0]['thumb_location'] ?: $commentData['itemData']['attachedImages'][0]['location'] );
				}
				elseif( is_subclass_of( $commentData['indexData']['index_class'], 'IPS\Content\Item' ) AND $contentImage = $item->contentImages( 1, TRUE ) )
				{
					$attachType = key( $contentImage[0] );
					$fileObj = File::get( $attachType, $contentImage[0][ $attachType ] );
				}

				if( $fileObj !== NULL )
				{
					if ( $fileObj->isImage() )
					{
						$fileContents = $fileObj->contents();
						if ( \strlen( $fileContents ) > 1000000 )
						{
							throw new \OutOfRangeException;
						}

						$attachment = [
							'name' => $fileObj->originalFilename,
							'file' => base64_encode( $fileContents )
						];
					}
				}
			}
			catch( Exception | \UnderflowException | \OutOfRangeException $e ) { }

			/* Truncate content */
			if( mb_strlen( $commentData['indexData']['index_content'] ) > 500 )
			{
				$commentData['indexData']['index_content'] = trim( mb_substr( $commentData['indexData']['index_content'], 0, 500 ) ) . '...';
			}

			/* Comment Count */
			$commentCount = NULL;
			if( $item instanceof Item )
			{
				$commentCount = $item->commentCount();

				if( $item::$firstCommentRequired AND $commentCount > 0 )
				{
					$commentCount--;
				}
			}

			$classObj = $commentClass::load( $commentData['indexData']['index_object_id'] );
			$return[] = array(
				'title'		=> $item->mapped('title'),
				'content'	=> $commentData['indexData']['index_content'],
				'date'      => $commentData['indexData']['index_date_commented'],
				'author'    => $classObj->isAnonymous() ? \IPS\Member::loggedIn()->language()->get( "post_anonymously_placename" ) : $commentData['authorData']['name'],
				'key1'      => $itemClass . '/' . $commentData['indexData']['index_item_id'],
				'key2'      => $commentClass . '/' . $commentData['indexData']['index_object_id'],
				'replies'   => $commentCount,
				'reactions' => IPS::classUsesTrait( $itemClass, 'IPS\Content\Reactable' ) ? $item->reactionCount() : NULL,
				'image'     => $attachment
			);
		}

		Output::i()->json([
			'results' => $return
		]);
	}

	/**
	 * Sync Hive Membership data
	 *
	 * @return void
	 */
	protected function _syncMembers()
	{
		if( !isset( $this->payload['sync_data'] ) OR !\count( $this->payload['sync_data'] ) )
		{
			Output::i()->json( [ 'error' => 'Missing sync data' ], 400 );
		}

		$memberIds = array_keys( $this->payload['sync_data'] );
		$memberData = new ActiveRecordIterator( Db::i()->select( '*', 'core_members', [ Db::i()->in( 'member_id', $memberIds ) ] ), 'IPS\Member' );
		$respond = [];
		$seen = [];

		foreach( $memberData as $member )
		{
			$generatedHash = Hive::groupHash( $member );

			if( $generatedHash !== $this->payload['sync_data'][ $member->member_id ] )
			{
				$respond[ $member->member_id ] = $generatedHash;
			}
			$seen[] = $member->member_id;
		}

		/* Record subscribe confirm */
		Db::i()->update( 'core_hive_subscribers', [ 'subscribe_confirmed' => 1 ], [ [ 'subscribe_confirmed=?', 0 ], [ Db::i()->in( 'member_id', $memberIds ) ] ] );

		/* Those that haven't been seen, thus deleted */
		$deleted = array_diff( $memberIds, $seen );

		foreach( $deleted as $del )
		{
			$respond[ $del ] = 'guest';
		}

		Output::i()->json( $respond );
	}

	/**
	 * Unfollow
	 *
	 * @return void
	 */
	protected function _unfollow()
	{
		Db::i()->delete( 'core_hive_subscribers', [ 'member_id=?', (int) $this->payload['member_id'] ] );
		Output::i()->json( 'ok' );
	}

	/**
	 * Verify JWT
	 *
	 * @return void
	 */
	protected function _verifyJwt()
	{
		/* Make sure the request is PUT */
		if( ! isset( $_SERVER['REQUEST_METHOD'] ) OR mb_strtoupper( $_SERVER['REQUEST_METHOD'] ) !== 'POST' )
		{
			Output::i()->json( array( 'error' => 'Invalid HTTP Method' ), 400 );
		}

		/* Make sure we have the hive key (activated) */
		if( !\IPS\core\Hive::enabled() )
		{
			Output::i()->json( array( 'error' => 'Community Hive not enabled' ), 404 );
		}

		/* Do we have the JWT */
		$token = file_get_contents('php://input');
		if( empty( $token ) )
		{
			Output::i()->json( array( 'error' => 'Missing JWT' ), 401 );
		}

		$jwk = new \Jose\Component\Core\JWK([
			'kty' => 'oct',
			'k' => base64_encode( Settings::i()->hive_key )
		]);

		$jwsVerifier = new \Jose\Component\Signature\JWSVerifier(
			new \Jose\Component\Core\AlgorithmManager([ new \Jose\Component\Signature\Algorithm\HS256() ])
		);

		$jwsCompactSerializer = new CompactSerializer();

		$data = $jwsCompactSerializer->unserialize( $token );
		if( !$jwsVerifier->verifyWithKey( $data, $jwk, 0 ) )
		{
			Output::i()->json( array( 'error' => 'Invalid JWT' ), 401 );
		}

		$this->payload = json_decode( $data->getPayload(), true );

		$claimCheckerManager = new ClaimCheckerManager(
			[
				new IssuerChecker( ['communityhive'] ),
				new AudienceChecker( rtrim( Settings::i()->base_url, '/' ) ),
				new IssuedAtChecker( 1000 ),
				new NotBeforeChecker( 1000 ),
				new ExpirationTimeChecker( 1000 )
			]
		);

		try
		{
			$claimCheckerManager->check( $this->payload, [ 'iss', 'sub', 'exp', 'aud', 'nbf', 'iat' ] );
		}
		catch( MissingMandatoryClaimException | InvalidClaimException $e )
		{
			Output::i()->json( array( 'message' => $e->getMessage() ), 400 );
		}

		parent::execute();
	}
}