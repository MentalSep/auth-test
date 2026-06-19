<?php

namespace Company\AuthClient;

use Company\AuthClient\Http\Middleware\CheckAuth;
use Company\AuthClient\Http\Middleware\CheckPermission;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AuthClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sso.php', 'sso');
        $this->app->singleton('sso-auth', SSOAuth::class);
        $this->app->singleton(SSOClient::class);
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('auth.central', CheckAuth::class);
        $router->aliasMiddleware('check.permission', CheckPermission::class);
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->publishes([
            __DIR__.'/../config/sso.php' => config_path('sso.php'),
        ], 'sso-config');
    }
}
