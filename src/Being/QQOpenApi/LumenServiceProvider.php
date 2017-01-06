<?php

namespace Being\QQOpenApi;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package Cviebrock\LaravelElasticsearch
 */
class LumenServiceProvider extends BaseServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
