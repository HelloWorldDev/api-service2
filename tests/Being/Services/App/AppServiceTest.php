<?php

namespace Tests\Being\Services\App;

use Being\Services\App\AppService;
use Tests\Being\Laravel\Lumen\TestCase;

class AppServiceTest extends TestCase
{
    protected $request;

    public function setUp()
    {
        $this->request = new Request();
        parent::setUp();
    }

    public function testLog()
    {
        AppService::debug('hello', __FILE__, __LINE__);
        AppService::error('nihao', __FILE__, __LINE__);
        $this->assertTrue(true);
    }

    public function testResponse()
    {
        $this->assertTrue(AppService::response() instanceof \Symfony\Component\HttpFoundation\Response);
        $this->assertTrue(AppService::responseError(500) instanceof \Symfony\Component\HttpFoundation\Response);
    }

    public function testAppClientTypeCheck()
    {
        $this->assertTrue(is_bool(AppService::isAndroidAppClient()));
        $this->assertTrue(is_bool(AppService::isiOSAppClient()));
    }

    public function testLimit()
    {
        $this->assertTrue(AppService::limit($this->request) === 30);
        $this->assertTrue(AppService::limit($this->request, 10, 100, 'user_limit') === 100);
    }
}

class Request
{
    public function input($key = null, $default = null)
    {
        $params = [
            'limit' => 30,
            'user_limit' => 120,
        ];

        return is_null($key) ? $params : (array_key_exists($key, $params) ? $params[$key] : $default);
    }
}
