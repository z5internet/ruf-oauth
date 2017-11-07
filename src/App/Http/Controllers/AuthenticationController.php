<?php namespace z5internet\RufOAuth\App\Http\Controllers;

use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\CryptKey;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;
use z5internet\RufOAuth\App\Bridge\AccessTokenRepository;
use z5internet\RufOAuth\App\ClientRepository;
use z5internet\RufOAuth\App\TokenRepository;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

class AuthenticationController extends Controller {

    protected $server;

    public function __construct() {

		$this->clients = new ClientRepository;

		$this->tokens = new TokenRepository;

		$this->server = new ResourceServer(
            app()->make(AccessTokenRepository::class),
			new CryptKey(config('oauth.keys.public'), null, false)
        );

    }

	public function checkAuthentication() {

		$request = app('request');

        // First, we will convert the Symfony request to a PSR-7 implementation which will
        // be compatible with the base OAuth2 library. The Symfony bridge can perform a
        // conversion for us to a Zend Diactoros implementation of the PSR-7 request.
        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);

            $user = UserController::getUser($psr->getAttribute('oauth_user_id'));

            if (! $user) {
                return;
            }

            // Next, we will assign a token instance to this user which the developers may use
            // to determine if the token has a given scope, etc. This will be useful during
            // authorization such as within the developer's Laravel model policy classes.
            $token = $this->tokens->find(
                $psr->getAttribute('oauth_access_token_id')
            );

            $clientId = $psr->getAttribute('oauth_client_id');

            // Finally, we will verify if the client that issued this token is still valid and
            // its tokens may still be used. If not, we will bail out since we don't want a
            // user to be able to send access tokens for deleted or revoked applications.
            if ($this->clients->revoked($clientId)) {
                return;
            }

            return $token ? $user->withAccessToken($token) : null;
        } catch (OAuthServerException $e) {
            return Container::getInstance()->make(
                ExceptionHandler::class
            )->report($e);
        }

	}


}