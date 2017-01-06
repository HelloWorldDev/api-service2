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
     Success Response:
        Array
        (
        [ret] => 0
        [is_lost] => 0
        [nickname] => 小蜜蜂
        [gender] => 男
        [country] => 中国
        [province] => 河南
        [city] => 许昌
        [figureurl] => http://thirdapp1.qlogo.cn/qzopenapp/95dac2d0238d3f7b0e6b97989772f0a149006159d6a361078a2bbaea5bcff481/50
        [is_yellow_vip] => 0
        [is_yellow_year_vip] => 0
        [yellow_vip_level] => 0
        [is_yellow_high_vip] => 0
        )
     */
    public function testGetUserInfo()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        /** @var QQClient $client */
        $client = new \Being\QQOpenApi\QQClient('', '');
        $client->setServerName('openapi.tencentyun.com');
        //$client->setServerName('119.147.19.43');
        //$client = app(QQClient::class);
        var_dump(config('qq_open_api'));
        $userInfo = $client->getUserInfo('DBC6FD0278791B26584E5C06D592C950', 'E3D6362FC09B9FC56F46D080415FD4C2', 'openmobile_ios');
        //print_r($userInfo);
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
