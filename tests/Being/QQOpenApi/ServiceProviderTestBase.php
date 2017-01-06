<?php

namespace Tests\Being\QQOpenApi;

use Being\QQOpenApi\QQClient;

abstract class ServiceProviderTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     *
     Error Response:
        Array
        (
        [ret] => 1801
        [msg] => openid is empty
        )
     */
    public function testGetUserInfo()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        /** @var QQClient $client */
        $client = app(QQClient::class);
        $userInfo = $client->getUserInfo('', '', '');
        // print_r($userInfo);
        $this->assertTrue(isset($userInfo['ret']));
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
