<?php

namespace Being\Api\Service\Thirdparty;

use Being\WeiboOpenApi\WeiboClient;
use Log;

class AuthWeibo extends Auth
{
    public function login($unionid, $code)
    {
        $client = app(WeiboClient::class);
        $client->setAccessToken($code);
        $userInfo = $client->show_user_by_id($unionid);
        Log::debug(sprintf('%s:%d weibo response:%s', __FILE__, __LINE__, json_encode($userInfo)));
        $nickname = empty($userInfo['name']) ? '' : $userInfo['name'];
        $avatar = empty($userInfo['avatar_large']) ? '' : $userInfo['avatar_large'];

        return ['nickname' => $nickname, 'avatar' => $avatar];
    }
}