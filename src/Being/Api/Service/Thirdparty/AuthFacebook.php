<?php

namespace Being\Api\Service\Thirdparty;

class AuthFacebook extends Auth
{
    public function setConfig($config)
    {
        return $this;
    }

    public function login($unionid, $code)
    {
        return ['unionid' => $unionid, 'code' => $code];
    }
}