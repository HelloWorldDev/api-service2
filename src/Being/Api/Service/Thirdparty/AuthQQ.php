<?php

namespace Being\Api\Service\Thirdparty;

use Being\QQOpenApi\QQClient;
use Being\Services\App\AppService;

class AuthQQ extends Auth
{
    private $serverName;
    private $appId;
    private $appKey;
    private $pf;

    public function setConfig($config)
    {
        $this->serverName = $config['qq']['server_name'];
        $this->appId = $config['qq']['app_id'];
        $this->appKey = $config['qq']['app_key'];
        $this->pf = $config['qq']['pf'];
        return $this;
    }

    public function login($unionId, $code)
    {
        $client = new QQClient($this->appId, $this->appKey);
        $client->setServerName($this->serverName);
        $userInfo = $client->getUserInfo($unionId, $code, $this->pf);
        AppService::debug('qq response:'.json_encode($userInfo), __FILE__, __LINE__);
        $nickname = empty($userInfo['nickname']) ? '' : $userInfo['nickname'];
        $avatar = empty($userInfo['figureurl']) ? '' : $userInfo['figureurl'];
        return ['unionid' => $unionId, 'code' => $code, 'avatar' => $avatar, 'nickname' => $nickname];
    }
}
