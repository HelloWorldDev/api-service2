<?php

namespace Being\WeiboOpenApi;

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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeiboClient::class, function ($app) {
            return new WeiboClient(config('weibo_open_api.wb_akey'), config('weibo_open_api.wb_skey'), null, null);
        });
    }
}
