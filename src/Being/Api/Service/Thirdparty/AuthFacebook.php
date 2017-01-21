<?php

namespace Being\Api\Service\Thirdparty;


class AuthFacebook extends Auth
{

    public function login($unionid, $code)
    {
        return ['unionid' => $unionid, 'code' => $code];
    }
}