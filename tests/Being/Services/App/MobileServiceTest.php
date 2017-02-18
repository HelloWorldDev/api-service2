<?php

namespace Tests\Being\Services\App;

use Being\Services\App\MobileService;
use Tests\Being\Laravel\Lumen\TestCase;

class MobileServiceTest extends TestCase
{
    public function testFormatMobile()
    {
        $tests = [
            '+8613510041047' => MobileService::formatMobile('13510041047', 'cn'),
            '13510041047' => MobileService::formatMobile('13510041047', ''),
        ];

        foreach ($tests as $key => $val) {
            $this->assertTrue($key == $val);
        }
    }

    public function testParseMobile()
    {
        $ret = MobileService::parseMobile('+8613510041047', 'cn');
        $this->assertTrue(
            $ret['mobile'] == '13510041047'
            && $ret['country'] == 'CN'
            && $ret['code'] == 86
        );
    }
}
