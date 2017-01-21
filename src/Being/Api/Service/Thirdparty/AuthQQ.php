<?php

namespace Being\Api\Service\Thirdparty;

use Being\QQOpenApi\QQClient;
use Being\Services\App\AppService;

class AuthQQ extends Auth
{
    public function login($unionid, $code)
    {
        $config = config('qq_open_api');
        $keyPair = null;
        if (AppService::isiOSAppClient()) {
            $keyPair = $config['ios'];
        } elseif (AppService::isAndroidAppClient()) {
            $keyPair = $config['android'];
        }
        if (is_null($keyPair)) {
            return null;
        }

        $client = new QQClient($keyPair['app_id'], $keyPair['app_key']);
        $client->setServerName($config['server_name']);
        $userInfo = $client->getUserInfo($unionid, $code, $keyPair['pf']);
        AppService::debug('qq response:'.json_encode($userInfo), __FILE__, __LINE__);
        $nickname = empty($userInfo['nickname']) ? '' : $userInfo['nickname'];
        $avatar = empty($userInfo['figureurl']) ? '' : $userInfo['figureurl'];
        return ['unionid' => $unionid, 'code' => $code, 'avatar' => $avatar, 'nickname' => $nickname];
    }
}