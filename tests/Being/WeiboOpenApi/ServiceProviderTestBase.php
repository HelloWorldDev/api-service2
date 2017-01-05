<?php

namespace Tests\Being\WeiboOpenApi;

use Being\WeiboOpenApi\WeiboClient;
use Illuminate\Container\Container;

abstract class ServiceProviderTestBase extends \PHPUnit_Framework_TestCase
{
    public function testGetUserInfo()
    {
        // fix the cli mode lost the "$_SERVER['REMOTE_ADDR']", crash by saetv2.ex.class.php:395
        define('SAE_ACCESSKEY', true);

        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        $client = app(WeiboClient::class);
        $userInfo = $client->show_user_by_id(null);
        var_dump($userInfo);
        $this->assertTrue(true);
    }

    /**
     * @return Container
     */
    abstract protected function setupApplication();

    /**
     * @param Container $app
     * @return mixed
     */
    abstract protected function setupServiceProvider($app);
}
