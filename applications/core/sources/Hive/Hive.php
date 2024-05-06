<?php
/**
 * @brief		Community Hive Helpers
 * @author		<a href='https://communityhive.com'>CommunityHive.com</a>
 * @copyright	(c) CommunityHive.com
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Community Hive
 * @since		16 May 2023
 */

namespace IPS\core;

/* To prevent PHP errors (extending class does not exist) revealing path */

use IPS\Application;
use IPS\Content;
use IPS\Content\Item;
use IPS\Content\Searchable;
use IPS\Http\Url;
use IPS\IPS;
use IPS\Member;
use IPS\Settings;
use Jose\Component\Core\JWK;
use IPS\Db;

if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Community Hive Helpers
 */
class _Hive extends \IPS\Patterns\Singleton
{
	/**
	 * Framework coding standards....
	 */
	public function __construct()
	{

	}

	/**
	 * Make an API request with a JWT
	 *
	 * @param   string  $endpoint
	 * @param   array   $payload
	 * @return  array
	 */
	public static function api( string $endpoint, array $payload ): array
	{
		$hostname = \defined( 'COMMUNITYHIVE_URL' ) ? COMMUNITYHIVE_URL : 'https://communityhive.com';
		$request = Url::external( $hostname . '/api/v1/' . $endpoint )
			->request(3)
			->put( static::_assembleJwt( $payload ) );

		if( (int) $request->httpResponseCode !== 200 )
		{
			\IPS\Log::log( [ 'endpoint' => $hostname . '/api/v1/' . $endpoint, 'payload' => $payload, 'response' => $request ], 'hive_error' );
			throw new \LogicException( $request->decodeJson()['error'] ?? 'an unknown error occurred.' );
		}

		return $request->decodeJson();
	}

	/**
	 * Is Community Hive fully enabled?
	 *
	 * @return bool
	 */
	public static function enabled(): bool
	{
		return ( new \IPS\core\extensions\core\CommunityEnhancements\Hive )->enabled;
	}

	/**
	 * Generate group hash in an ordered way
	 *
	 * @param   Member  $member
	 * @return  string
	 */
	public static function groupHash( Member $member ): string
	{
		if( $member->isBanned() === FALSE )
		{
			$hash = array_filter( array_merge( [ $member->member_group_id ], explode( ',', $member->mgroup_others ) ) );
			natcasesort( $hash );
			return implode( ',', $hash );
		}
		else
		{
			return 'guest';
		}
	}

	/**
	 * @brief   store any keys for removal in __destruct()
	 */
	protected static array $_deleteQueue = [];

	/**
	 * Remove content from Hive - Used if something is moved or content is hidden
	 *
	 * @param   Content $content
	 * @return  void
	 */
	public function removeContent( Content $content )
	{
		/* Wrap it all because we don't want it to stop the moderation action */
		try
		{
			if ( !( $content instanceof Searchable ) or !\IPS\core\Hive::enabled() )
			{
				return;
			}
			$idColumn = $content::$databaseColumnId;

			if ( $content instanceof Item )
			{
				$key1 = \get_class( $content ) . '/' . $content->$idColumn;
			}
			else
			{
				$itemIdColumn = $content->item()::$databaseColumnId;
				$key1 = \get_class( $content->item() ) . '/' . $content->item()->$itemIdColumn;
				$key2 = \get_class( $content ) . '/' . $content->$idColumn;
			}

			/* Store IDs for removal */
			static::$_deleteQueue[] = [ 'key1' => $key1, 'key2' => $key2 ?? NULL ];
		}
		catch( \Exception | \Throwable $e )
		{
			\IPS\Log::log( $e, 'hive_content_removal' );
		}
	}

	/**
	 * Assemble a JWT with payload
	 *
	 * @param   array   $payload
	 * @return  string
	 */
	protected static function _assembleJwt( array $payload ): string
	{
		IPS::$PSR0Namespaces['Jose']       = \IPS\ROOT_PATH . '/system/3rd_party/JwtFramework/src';
		IPS::$PSR0Namespaces['Base64Url']  = \IPS\ROOT_PATH . '/system/3rd_party/Base64Url';

		$jwk = new JWK([
			'kty' => 'oct',
			'k' => base64_encode( Settings::i()->hive_site_key )
		]);

		$jwsCompactSerializer = new \Jose\Component\Signature\Serializer\CompactSerializer();
		$jwsBuilder = new \Jose\Component\Signature\JWSBuilder( new \Jose\Component\Core\AlgorithmManager( [ new \Jose\Component\Signature\Algorithm\HS256() ] ) );

		$defaultPayload = [
			'sub'       => Settings::i()->hive_site_id,
			'iss'		=> rtrim( Settings::i()->base_url, '/' ),
			'iat'		=> time(),
			'nbf'		=> time(),
			'exp'		=> time() + 60,
			'aud'		=> 'communityhive',
			'hive_system_version' => Application::load('core')->long_version
		];

		$jws = $jwsBuilder
			->create()
			->withPayload( json_encode( array_merge( $defaultPayload, $payload ) ) )
			->addSignature( $jwk, [
				'typ' => 'JWT',
				'alg' => 'HS256',
			])
			->build();

		return $jwsCompactSerializer->serialize( $jws, 0 );
	}

	/**
	 * @brief   Cache whether the member is subscribed
	 */
	protected static ?bool $_showPromotion = NULL;

	/**
	 * Check if we should show the Hive Promotion for the viewing member
	 *
	 * @return	bool
	 */
	public static function showPromotion(): bool
	{
		if( static::$_showPromotion !== NULL )
		{
			return static::$_showPromotion;
		}

		if ( !\IPS\core\Hive::enabled() )
		{
			return static::$_showPromotion = FALSE;
		}

		try 
		{
			Db::i()->select('member_id', 'core_hive_subscribers', [ 'member_id=?', Member::loggedIn()->member_id ] )->first();
			return static::$_showPromotion = FALSE;
		}
		catch ( \UnderflowException $e ){}

		return static::$_showPromotion = TRUE;
	}

	/**
	 * Send API request to remove any content IDs that have been queued
	 */
	public function __destruct()
	{
		/* Wrap it all because we don't want it to stop the moderation action */
		try
		{
			if( \count( static::$_deleteQueue ) )
			{
				static::api( 'contentremove', [ 'keys' => static::$_deleteQueue ] );
			}
		}
		catch( \Exception | \Throwable $e )
		{
			\IPS\Log::log( $e, 'hive_content_removal' );
		}
	}

	/**
	 * Site ID Check
	 * 
	 * @return bool
	 */
	public static function isSiteIdValid(): bool
	{
		$hostname = \defined( 'COMMUNITYHIVE_URL' ) ? COMMUNITYHIVE_URL : 'https://communityhive.com';

		if( empty( Settings::i()->hive_site_id ) ) 
		{
			return FALSE;
		}

		/* Check if the site ID is valid */
		$request = Url::external( $hostname . '/api/v1/siteidcheck')
		->request()
		->put( json_encode([
			'hive_site_id' => Settings::i()->hive_site_id,
			'hive_system_version' => \IPS\Application::load('core')->version
			]) );
		
		if( (int) $request->httpResponseCode == 200 )
		{
			$response = $request->decodeJson();

			/* If success, do nothing */
			if ( isset( $response['success'] ) AND $response['success'] )
			{
				return TRUE;
			}

			/* Site ID doesn't exist so we need to reset */
			Settings::i()->changeValues([
				'hive_key' => NULL,
				'hive_site_id' => NULL,
				'hive_site_key' => NULL,
				'hive_enabled' => FALSE
			]);

			return FALSE;
		}

		// If we get here than the request failed for some other reason so we'll just assume the site id is valid
		return TRUE;
	}
}