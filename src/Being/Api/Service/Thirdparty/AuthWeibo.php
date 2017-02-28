<?php

namespace Being\Api\Service\Thirdparty;

use Being\Services\App\AppService;
use Being\WeiboOpenApi\WeiboClient;

class AuthWeibo extends Auth
{
    public function setConfig($config)
    {
        return $this;
    }

    public function login($unionid, $code)
    {
        $client = app(WeiboClient::class);
        $client->setAccessToken($code);
        $userInfo = $client->show_user_by_id($unionid);
        AppService::debug('weibo response:' . json_encode($userInfo), __FILE__, __LINE__);
        $nickname = empty($userInfo['name']) ? '' : $userInfo['name'];
        $avatar = empty($userInfo['avatar_large']) ? '' : $userInfo['avatar_large'];

        return ['unionid' => $unionid, 'code' => $code, 'nickname' => $nickname, 'avatar' => $avatar];
    }
}
