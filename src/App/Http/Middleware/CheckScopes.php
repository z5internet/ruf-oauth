<?php namespace z5internet\RufOAuth\App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use z5internet\RufOAuth\Exceptions\MissingScopeException;

class CheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Laravel\Passport\Exceptions\MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {

        $r = app('auth')->guard('api')->user();

        if (! $r || ! $r->token()) {

            throw new AuthenticationException;

        }

        foreach ($scopes as $scope) {

            if (! $r->tokenCan($scope)) {

                throw new MissingScopeException($scope);

            }

        }

        return $next($request);

    }

}
