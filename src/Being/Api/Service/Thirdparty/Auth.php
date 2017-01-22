<?php

namespace Being\Api\Service\Thirdparty;

abstract class Auth
{
    abstract public function login($unionid, $code);

    public static function factory($type, $httpClient, $appId = '', $secret = '')
    {
        switch ($type) {
            case ThirdpartyAuth::TYPE_WETHAT:
                return new AuthWechat($httpClient, $appId, $secret);
            case ThirdpartyAuth::TYPE_FACEBOOK:
                return new AuthFacebook();
            case ThirdpartyAuth::TYPE_WEIBO:
                return new AuthWeibo();
            case ThirdpartyAuth::TYPE_QQ:
                return new AuthQQ();
            default:
                return null;
        }
    }
}
