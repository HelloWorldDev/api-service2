<?php

namespace Being\Api\Service\Thirdparty;

abstract class Auth
{
    abstract public function login($unionid, $code);
    abstract public function setConfig($config);

    public static function factory($type, $httpClient)
    {
        switch ($type) {
            case ThirdpartyAuth::TYPE_WETHAT:
                return new AuthWechat($httpClient);
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
