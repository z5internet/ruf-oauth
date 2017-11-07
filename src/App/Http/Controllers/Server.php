<?php namespace z5internet\RufOAuth\App\Http\Controllers;

use z5internet\RufOAuth\App\Bridge\ClientRepository as ClientRepositoryBridge;
use z5internet\RufOAuth\App\Bridge\AccessTokenRepository;
use z5internet\RufOAuth\App\Bridge\ScopeRepository;
use z5internet\RufOAuth\App\Bridge\AuthCodeRepository;
use z5internet\RufOAuth\App\Bridge\RefreshTokenRepository;

use z5internet\RufOAuth\App\TokenRepository;
use z5internet\RufOAuth\App\ClientRepository;

use League\OAuth2\Server\CryptKey;

class Server {

	private static $server;

	private static $scopes;

	public static function server() {

		if (!self::$server) {

			return self::$server = self::start();

		}

	}

	public static function start() {

		self::tokensCan(config('oauth.scopes'));

		$clientRepository = new ClientRepositoryBridge(new ClientRepository()); // instance of ClientRepositoryInterface
		$scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
		$accessTokenRepository = new AccessTokenRepository(new TokenRepository()); // instance of AccessTokenRepositoryInterface
		$authCodeRepository = new AuthCodeRepository(); // instance of AuthCodeRepositoryInterface
		$refreshTokenRepository = new RefreshTokenRepository(new AccessTokenRepository(new TokenRepository())); // instance of RefreshTokenRepositoryInterface

		$privateKey = new CryptKey(config('oauth.keys.private'), null, false);

		$encryptionKey = config('oauth.encryptionKey'); // generate using base64_encode(random_bytes(32))

		// Setup the authorization server
		$server = new \League\OAuth2\Server\AuthorizationServer(
		    $clientRepository,
		    $accessTokenRepository,
		    $scopeRepository,
		    $privateKey,
		    $encryptionKey
		);

		$grant = new \League\OAuth2\Server\Grant\AuthCodeGrant(
		     $authCodeRepository,
		     $refreshTokenRepository,
		     new \DateInterval('PT10M') // authorization codes will expire after 10 minutes
		 );

		$grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month

		// Enable the authentication code grant on the server
		$server->enableGrantType(
		    $grant,
		    new \DateInterval('PT1H') // access tokens will expire after 1 hour
		);

		return $server;

	}

    private static function tokensCan(array $scopes) {

        static::$scopes = $scopes;

    }

    public static function scopesFor(array $ids)
    {
        return collect($ids)->map(function ($id) {
            if (isset(static::$scopes[$id])) {
                return new Scope($id, static::$scopes[$id]);
            }
            return;
        })->filter()->values()->all();
    }

    public static function hasScope($id)
    {
        return $id === '*' || array_key_exists($id, static::$scopes);
    }

}
