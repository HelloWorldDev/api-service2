<?php

namespace Being\WeiboOpenApi;

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
        $configPath = realpath(__DIR__ . '/config/weibo_open_api.php');
        $this->publishes([
            $configPath => config_path('weibo_open_api.php'),
        ]);
    }

    /**
     * Register bindings in the container.
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
