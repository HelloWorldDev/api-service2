<?php

namespace Tests\Being\Services\App;

use Being\Services\App\AppService;
use PHPUnit_Framework_TestCase;

class AppServiceTest extends PHPUnit_Framework_TestCase
{
    protected $request;

    public function setUp()
    {
        $this->request = new class
        {
            public function input($key = null, $default = null)
            {
                $params = [
                    'limit' => 30,
                    'user_limit' => 120,
                ];

                return is_null($key) ? $params : (array_key_exists($key, $params) ? $params[$key] : $default);
            }
        };
    }

    public function testLimit()
    {
        $this->assertTrue(AppService::limit($this->request) === 30);
        $this->assertTrue(AppService::limit($this->request, 10, 100, 'user_limit') === 100);
    }
}