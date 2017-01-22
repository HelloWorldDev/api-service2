<?php

namespace Tests\Being\Api;

use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;
use Being\Api\Service\User\User;
use Being\Api\Service\User\UserClient;
use PHPUnit_Framework_TestCase;
use Being\Services\ResourceService;
use ThirdpartyAuth;

class UserCenterTest extends PHPUnit_Framework_TestCase
{
    public function testUserClient()
    {
        $okBody = json_encode([
            'code' => Code::SUCCESS,
            'message' => "",
            'data' => ['id' => 1]
        ]);

        $badBody = json_encode([
            'code' => Code::REQUEST_TIMEOUT,
            'message' => "bad response",
            'data' => null
        ]);

        $responses = [
            // http_code, body, header, expect Code, is Data null
            [200, $okBody, [], Code::SUCCESS, false],
            [400, $badBody, [], Code::REQUEST_TIMEOUT, true],
        ];

        $user = new User(123, 'jason', 'jason w', '123', 'sdf@sdf.com', 'avatar');
        $msg = 'bad response';
        foreach ($responses as $resp) {
            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->register($user);
            $this->assertEquals($resp[3], $code);

            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->login($user);
            $this->assertEquals($resp[3], $code);

            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->updateUser($user);
            $this->assertEquals($resp[3], $code);

            $reqs = [
                [null, 'ssdf@sdf.com'],
                ['username', null],
            ];
            foreach ($reqs as $item) {
                $cli = $this->getUserCenterCli($resp);
                $user = new User(null, $item[0], null, null, $item[1], null);
                list($code, $data) = $cli->verify($user);
                $this->assertEquals($resp[3], $code);
            }
        }
    }

    public function getUserCenterCli($resp)
    {
        $httpCli = \Mockery::mock('Being\Api\Service\Sender');
        $httpCli->shouldReceive('send')->times(1)->andReturn([$resp[0], $resp[1], $resp[2]]);

        $cli = new UserClient($httpCli, '1', '987654321nihao');

        return $cli;
    }

    public function testRealService()
    {
        //$this->_testRealService();
    }

    public function _testRealService()
    {
        $baseUrl = 'http://localhost:8091';
        $httpCli = new HttpClient($baseUrl);
        $userCli = new UserClient($httpCli, '1', '987654321nihao');

        $user = new User(0, 'jason4', 'jason', '123456', 'email4@sdf.com', '');
        list($code, $body) = $userCli->register($user);
        $this->assertEquals($code, 0);
        $user->uid = $body['id'];

        list($code, $body) = $userCli->login($user);
        $this->assertEquals($code, 0);

        $user->email = 'new2@sdf.com';
        list($code, $body) = $userCli->updateUser($user);
        $this->assertEquals($body['email'], 'new2@sdf.com');

        list($code, $body) = $userCli->verify('email', 'sdf@sdf.com');
        $this->assertEquals($code, 0);

        //第三方登录用户-查找
        $ta1 = new ThirdpartyAuth($user->uid, 1, '123456', 'zhweibo');
        list($code, $body) = $userCli->find3user($ta1);
        $this->assertEquals($code, 0);

        //第三方登录用户-注册
        $type = ThirdpartyAuth::TYPE_WETHAT;
        $username = 'u' . $type . substr(md5(uniqid()), 0, 9) . rand(1000, 9999);
        $unionid = uniqid();
        $newTa = new ThirdpartyAuth(0, 1, $unionid, 'third_party_name');
        $newUser = new User(0, $username, '', '', '', '');
        list($code, $body) = $userCli->register3user($newTa, $newUser);
        $this->assertEquals($code, 0);
    }
}
