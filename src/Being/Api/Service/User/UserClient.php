<?php

namespace Being\Api\Service\User;

use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;
use Being\Api\Service\Sender;
use Being\Api\Service\Thirdparty\Auth;
use Being\Api\Service\Thirdparty\ThirdpartyAuth;
use Being\Services\App\AppService;

class UserClient implements ClientInterface
{
    protected $httpClient;
    public $appID;
    public $appSecret;

    public function __construct(Sender $httpClient, $appID, $appSecret)
    {
        $this->httpClient = $httpClient;
        $this->appID = $appID;
        $this->appSecret = $appSecret;
    }

    public function parseResponseBody($body)
    {
        $resp = json_decode($body, true);
        if (isset($resp['code'])) {
            if ($resp['code'] == Code::SUCCESS) {
                return [$resp['code'], $resp['data']];
            } elseif (isset($resp['message'])) {
                return [$resp['code'], $resp['message']];
            }
        }

        return [Code::EMPTY_BODY, null];
    }

    protected function getSecretData()
    {
        return [
            'app_id' => $this->appID,
            'app_secret' => $this->appSecret,
        ];
    }

    protected function getSecretHeader()
    {
        return [
            'App-ID' => $this->appID,
            'App-Secret' => $this->appSecret,
        ];
    }

    public function register(User $user)
    {
        $bodyArr = [
            'username' => $user->username,
            'password' => $user->password,
            'fullname' => $user->fullname,
            'email' => $user->email,
        ];

        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $req = HttpClient::getRequest(HttpClient::POST, 'v1/user', [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function updateUser(User $user)
    {
        $bodyArr = [];
        foreach (['password', 'fullname', 'email'] as $key) {
            $val = $user->$key;
            if (!is_null($val)) {
                $bodyArr[$key] = $val;
            }
        }

        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = sprintf("/v1/user/%s", $user->uid);
        $req = HttpClient::getRequest(HttpClient::PUT, $uri, [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function login(User $user)
    {
        if (!empty($user->username)) {
            $type = 'username';
            $account = $user->username;
        } elseif (!empty($user->email)) {
            $type = 'email';
            $account = $user->email;
        } else {
            throw new \Exception("both username and email is empty");
        }

        $bodyArr = [
            'type' => $type,
            'account' => $account,
            'password' => $user->password,
        ];

        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = 'v1/login';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function verify(User $user)
    {
        $bodyArr = [];
        foreach (['username', 'password', 'fullname', 'email'] as $key) {
            $val = $user->$key;
            if (!is_null($val)) {
                $bodyArr[$key] = $val;
            }
        }

        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = 'v1/user/verify';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function find3user(ThirdpartyAuth $ta)
    {
        $header = $this->getSecretHeader();
        $uri = 'v1/thirdparty/user?unionid=' . $ta->unionid . '&type=' . $ta->type;
        $req = HttpClient::getRequest(HttpClient::GET, $uri, [], $header, []);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function register3user(ThirdpartyAuth $ta, User $user)
    {
        $bodyArr = [
            'username' => $user->username,
            'fullname' => $user->fullname,
            'tpname' => $ta->tpname,
            'unionid' => $ta->unionid,
            'type' => $ta->type,
        ];
        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = 'v1/thirdparty/signup';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }

    public function login3user($unionid, $code, $type, $config)
    {
        // 验证第三方登录信息
        $thirdparty = Auth::factory($type, $this->httpClient);
        if (is_null($thirdparty)) {
            AppService::error('unknow third party type:' . $type, __FILE__, __LINE__);
            return [Code::INVALID_PARAM, 'params error'];
        }
        $thirdInfo = $thirdparty->setConfig($config)->login($unionid, $code);
        if (is_null($thirdInfo)) {
            AppService::error('third party check fail', __FILE__, __LINE__);
            return [Code::SYSTEM_ERROR, 'params error'];
        }

        // 查看之前是否已注册
        $ta = new ThirdpartyAuth(null, $type, $thirdInfo['unionid'], '');
        list($code, $data) = $this->find3user($ta);
        if ($code == Code::SUCCESS || $code != Code::USER_NOT_EXISTS) {
            return [$code, $data];
        }

        // 用户不存在，进行注册
        $username = $this->randUserName($type);
        $tpname = isset($thirdInfo['nickname']) ? $thirdInfo['nickname'] : '';
        $ta->tpname = $tpname;
        $user = new User(0, $username, '', '', '', '');
        list($code, $data) = $this->register3user($ta, $user);
        if ($code != Code::SUCCESS) {
            return [$code, $data];
        }

        $data['nickname'] = $tpname;
        $data['avatar'] = isset($thirdInfo['avatar']) ? $thirdInfo['avatar'] : '';
        return [$code, $data];
    }

    private function randUserName($type)
    {
        //生成以u+type为前缀的15位长度用户名
        return 'u' . $type . substr(md5(uniqid()), 0, 9) . rand(1000, 9999);
    }
}
