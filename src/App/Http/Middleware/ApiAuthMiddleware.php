<?php namespace z5internet\RufOAuth\App\Http\Middleware;

use Closure;

use z5internet\RufOAuth\App\Http\Controllers\Server;

use z5internet\RufOAuth\App\Bridge\AccessTokenRepository;
use z5internet\RufOAuth\App\TokenRepository;

use League\OAuth2\Server\CryptKey;

use z5internet\RufOAuth\App\Http\Controllers\AuthenticationController;

use Illuminate\Auth\AuthenticationException;

class ApiAuthMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if ((new AuthenticationController)->checkAuthentication()) {

            return $next($request);

        }

        throw new AuthenticationException();

    }

}
