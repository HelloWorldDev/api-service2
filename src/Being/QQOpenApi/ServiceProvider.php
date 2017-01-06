<?php

namespace Being\QQOpenApi;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = realpath(__DIR__ . '/config/qq_open_api.php');
        $this->publishes([
            $configPath => config_path('qq_open_api.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(QQClient::class, function ($app) {
            $config = config('qq_open_api');
            $sdk = new QQClient($config['app_id'], $config['app_key']);
            $sdk->setServerName($config['server_name']);

            return $sdk;
        });
    }
}
