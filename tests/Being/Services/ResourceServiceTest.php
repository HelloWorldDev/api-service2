<?php

namespace Tests\Being\Services;

use PHPUnit_Framework_TestCase;
use Being\Services\ResourceService as BeingResourceService;

class ResourceServiceTest extends PHPUnit_Framework_TestCase
{
    public function testUrl2Key()
    {
        $tests = [
            'http://www.cloudfront.com/pubsub-dev/aaaaa/bbbbb' => 'aaaaa/bbbbb',
            'http://www.cloudfront.com/pubsub-dev/aaaaa/bbbbb?a=b' => 'aaaaa/bbbbb',
            'http://www.amazonaws.com/pubsub-dev/aaaaa/bbbbb' => 'aaaaa/bbbbb',
            'http://www.amazonaws.com/pubsub-dev/aaaaa/bbbbb?a=b' => 'aaaaa/bbbbb',
            'http://www.qiniu.com/pubsub-dev/aaaaa/bbbbb' => 'pubsub-dev/aaaaa/bbbbb',
            'http://www.qiniu.com/pubsub-dev/aaaaa/bbbbb?a=b?a=b' => 'pubsub-dev/aaaaa/bbbbb',
            'aaaaa/bbbbb' => 'aaaaa/bbbbb',
            'aaaaa/bbbbb?a=b' => 'aaaaa/bbbbb',
            'http://www.cloudfront.com/pubsub-dev' => 'pubsub-dev',
            'http://www.cloudfront.com/pubsub-dev?a=b' => 'pubsub-dev',
        ];

        foreach ($tests as $url => $key) {
            if (BeingResourceService::url2key($url) != $key) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
            }
        }
    }
}