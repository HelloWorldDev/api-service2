<?php

namespace Being\Api\Service\User;

use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;
use Being\Api\Service\Sender;
use ThirdpartyAuth;

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
            } elseif (isset($resp['message'])){
                return [$resp['code'], $resp['message']];
            }
        }

        return [Code::EMPTY_BODY, null];
    }

    protected function getSecretData(){
        return [
            'app_id' => $this->appID,
            'app_secret' => $this->appSecret,
        ];
    }

    protected function getSecretHeader(){
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
        $bodyArr = [
            'unionid' => $ta->unionid,
            'type' => $ta->type,
        ];
        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = 'v1/thirdparty/user';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], $header, $bodyArr);
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
}
