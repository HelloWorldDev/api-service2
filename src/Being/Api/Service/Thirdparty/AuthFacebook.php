<?php

namespace Being\Api\Service\Thirdparty;



use Being\Services\App\AppService;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Pheanstalk\Exception;

class AuthFacebook extends Auth
{
    private $appId;
    private $secret;

    public function setConfig($config)
    {
        $this->appId = $config['facebook']['app_id'];
        $this->secret = $config['facebook']['secret'];
        return $this;
    }

    public function login($unionid, $code=0)
    {
<<<<<<< HEAD
        $fb = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->secret,
            'default_graph_version' => 'v2.9',
        ]);

        try {
            //$response = $fb->get('/me?fields=id,name,first_name,last_name,picture,email', $unionid);
            $response = $fb->get('/me?fields=id,name,picture', $unionid);
        } catch(FacebookResponseException $e) {
            AppService::error('Graph returned an error: ' . $e->getMessage(), __FILE__, __FILE__);
            return [$e->getCode(), $e->getMessage()];
        }
        $me = $response->getGraphUser();
        $unionid = $me->getId();
        $avatar = $me->getPicture()->getUrl();
        $nickname = $me->getName();
        $code = 0;
        return ['unionid' => $unionid, 'code' => $code, 'avatar' => $avatar, 'nickname' => $nickname];
=======
        // todo
        return ['unionid' => $unionid, 'code' => $code];
>>>>>>> origin/master
    }
}
