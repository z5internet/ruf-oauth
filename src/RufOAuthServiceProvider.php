<?php namespace z5internet\RufOAuth;

use Illuminate\Support\ServiceProvider;

use z5internet\ReactUserFramework\App\Http\Controllers\AddRouteController;

use Laravel\Lumen\Application as LumenApplication;

use z5internet\RufOAuth\App\Http\Controllers\AuthenticationController;

class RufOAuthServiceProvider extends ServiceProvider
{

    public function boot()
    {

        if ($this->app instanceof LumenApplication) {

            $this->app->configure('oauth');

        }

        $this->route = new AddRouteController($this->app);

        require __DIR__.'/routes/OAuth.php';

        $this->commands([

            \z5internet\RufOAuth\App\Console\Commands\install::class,

        ]);

        $key = 'auth.guards';

        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($config, ['api' => ['driver' => 'api']]));

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app['auth']->viaRequest('api', function ($request) {

            $user = (new AuthenticationController($request))->checkAuthentication();

            return $user?$user:null;

        });

        $this->app->routeMiddleware([

            'RufOAuthApi' => App\Http\Middleware\ApiAuthMiddleware::class,
            'scopes' => App\Http\Middleware\CheckScopes::class,
            'scope' => App\Http\Middleware\CheckForAnyScope::class,

        ]);

    }

}
