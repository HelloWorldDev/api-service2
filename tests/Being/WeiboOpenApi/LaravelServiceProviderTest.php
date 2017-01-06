<?php

namespace Tests\Being\WeiboOpenApi;

use Being\WeiboOpenApi\ServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class LaravelServiceProviderTest extends ServiceProviderTestBase
{
    public function setUp()
    {
        if (!class_exists(Application::class)) {
            $this->markTestSkipped();
        }

        parent::setUp();
    }

    protected function setupApplication()
    {
        // Create the application such that the config is loaded.
        $app = new Application();
        $app->setBasePath(sys_get_temp_dir());
        $app->instance('config', new Repository());

        return $app;
    }

    protected function setupServiceProvider($app)
    {
        // Create and register the provider.
        $provider = new ServiceProvider($app);
        $app->register($provider);
        $provider->boot();

        return $provider;
    }
}
