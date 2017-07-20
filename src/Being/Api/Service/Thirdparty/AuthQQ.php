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
        AppService::debug('qq response:' . json_encode($userInfo), __FILE__, __LINE__);
        // {"ret":0,"is_lost":0,"nickname":"\u5b87\u97f3\u6052\u7ed5","gender":"\u7537","country":"\u6cd5\u56fd","province":"","city":"\u5df4\u9ece","figureurl":"http:\/\/thirdapp3.qlogo.cn\/qzopenapp\/d82c76955500aa4d30a7fdddeae58a218becdd11a8e0159b0eecad96680c04d7\/50","is_yellow_vip":0,"is_yellow_year_vip":0,"yellow_vip_level":0,"is_yellow_high_vip":0}
        if (!isset($userInfo['ret']) || !preg_match('/^[0-9]+$/', $userInfo['ret']) || $userInfo['ret'] != 0) {
            return null;
        }
        $nickname = empty($userInfo['nickname']) ? '' : $userInfo['nickname'];
        $avatar = empty($userInfo['figureurl']) ? '' : $userInfo['figureurl'];
        return ['unionid' => $unionId, 'code' => $code, 'avatar' => $avatar, 'nickname' => $nickname];
    }
}
