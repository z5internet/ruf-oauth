<?php namespace z5internet\RufOAuth\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\AddRouteController;

use Illuminate\Http\Request;

use z5internet\RufOAuth\App\ClientRepository;

use Psr\Http\Message\ServerRequestInterface;

use z5internet\RufOAuth\App\Bridge\User;

use Zend\Diactoros\Response as Psr7Response;

use League\OAuth2\Server\AuthorizationServer;

use App\User as UserModel;

use stdClass;

class Authorize {

    use HandlesOAuthErrors;

    protected $server;

    protected $response;

    public function __construct(ClientRepository $ClientRepository) {

        $this->server = Server::Server();

        $this->ClientRepository = new ClientRepository;

    }

	public function getAuthorizationFormInfo(Request $request) {

		$scopes = Server::scopesFor(explode(',', $request->input('scope')));

		return ['data' => [
			'client' => $this->ClientRepository->find($request->input('client_id')),
			'scopes' => $scopes,
		]];

	}

	public function authorize(ServerRequestInterface $psrRequest) {

        return $this->withErrorHandling(function () use ($psrRequest) {

            $authRequest = $this->server->validateAuthorizationRequest($psrRequest);

            // The auth request object can be serialized and saved into a user's session.
            // You will probably want to redirect the user at this point to a login endpoint.

            // Once the user has logged in set the user on the AuthorizationRequest

            $authRequest->setUser(new User($this->getUser())); // an instance of UserEntityInterface

            // At this point you should redirect the user to an authorization page.
            // This form will ask the user to approve the client and the scopes requested.

            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);

            // Return the HTTP redirect response
            return $this->server->completeAuthorizationRequest($authRequest, new Psr7Response);

        });

	}

    public function issueToken(ServerRequestInterface $psrRequest) {

        $response = $this->withErrorHandling(function () use ($psrRequest) {

            $r = $this->server->respondToAccessTokenRequest($psrRequest, new Psr7Response);

            return $r;

        });

        $data = json_decode($response->content());

        $response->setContent(collect(['data' => $data]));

        return $response;

    }

    private function getUser() {

        $uid = app('auth')->id();

        if ($uid) {

            return $uid;

        }

    }

}

