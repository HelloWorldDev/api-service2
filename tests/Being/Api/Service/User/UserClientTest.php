<?php

namespace Tests\Being\Api\Service\User;

use Being\Api\Auth;
use Being\Api\Service\User\UserClient;

class UserClientTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAccountExists()
    {
        $userClient = new UserClient(new Auth('aaa', 'bbb'));
        $userClient->setLogFile(__DIR__ . '/../../../../logs/user_client_test.log');
        $ret = $userClient->isAccountExists('liuyong@nihao.com');
        $this->assertTrue(is_int($ret));
    }

    public function testLogin()
    {
        $userClient = new UserClient(new Auth('aaa', 'bbb'));
        $userClient->setLogFile(__DIR__ . '/../../../../logs/user_client_test.log');
        $ret = $userClient->login('liuyong@nihao.com', '111111');
        $this->assertTrue(is_int($ret));
    }
}