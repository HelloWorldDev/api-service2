<?php

namespace Tests\Being\Api;

use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;
use Being\Api\Service\User\User;
use Being\Api\Service\User\UserClient;
use PHPUnit_Framework_TestCase;
use Being\Services\ResourceService;

class UserCenterTest extends PHPUnit_Framework_TestCase
{
    public function testUserClient()
    {
        $okBody = json_encode([
            'code' => Code::SUCCESS,
            'msg' => "",
            'data' => ['id' => 1]
        ]);

        $badBody = json_encode([
            'code' => Code::REQUEST_TIMEOUT,
            'msg' => "bad response",
            'data' => null
        ]);

        $responses = [
            // http code, body, header, expect Code, is Data null
            [200, $okBody, [], Code::SUCCESS, false],
            [400, $badBody, [], Code::REQUEST_TIMEOUT, true],
        ];

        $user = new User(123, 'jason', 'jason w', '123', 'sdf@sdf.com', 'avatar');
        foreach ($responses as $resp) {
            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->register($user);
            $this->assertEquals($resp[3], $code);
            $this->assertEquals($resp[4], is_null($data));

            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->login($user);
            $this->assertEquals($resp[3], $code);
            $this->assertEquals($resp[4], is_null($data));

            $cli = $this->getUserCenterCli($resp);
            list($code, $data) = $cli->updateUser($user);
            $this->assertEquals($resp[3], $code);
            $this->assertEquals($resp[4], is_null($data));

            $reqs = [
                ['email', 'ssdf@sdf.com'],
                ['username', 'jason'],
            ];
            foreach ($reqs as $item) {
                $cli = $this->getUserCenterCli($resp);
                list($code, $data) = $cli->verify($item[0], $item[1]);
                $this->assertEquals($resp[3], $code);
                $this->assertEquals($resp[4], is_null($data));
            }
        }
    }

    public function getUserCenterCli($resp)
    {
        $httpCli = \Mockery::mock('Being\Api\Service\Sender');
        $httpCli->shouldReceive('send')->times(1)->andReturn([$resp[0], $resp[1], $resp[2]]);

        $cli = new UserClient($httpCli);

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
        $userCli = new UserClient($httpCli);

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
    }
}
