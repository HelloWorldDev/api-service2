<?php

namespace Tests\Being\WeiboOpenApi;

use Being\WeiboOpenApi\LumenServiceProvider;
use Illuminate\Config\Repository;
use Laravel\Lumen\Application;

class LumenServiceProviderTest extends ServiceProviderTestBase
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
        $app = new Application(sys_get_temp_dir());
        $app->instance('config', new Repository());

        return $app;
    }

    protected function setupServiceProvider($app)
    {
        // Create and register the provider.
        $provider = new LumenServiceProvider($app);
        $app->register($provider);
        $provider->boot();

        return $provider;
    }
}
