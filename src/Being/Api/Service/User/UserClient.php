<?php

namespace Being\Api\Service\User;

use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;
use Being\Api\Service\Sender;

class UserClient implements ClientInterface
{
    protected $httpClient;

    public function __construct(Sender $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function parseResponseBody($body)
    {
        $resp = json_decode($body, true);
        if (isset($resp['code'])) {
            if ($resp['code'] == Code::SUCCESS) {
                return [$resp['code'], $resp['data']];
            } else {
                return [$resp['code'], $resp['message']];
            }
        }

        return [Code::EmptyBody, null];
    }

    public function register(User $user)
    {
        $bodyArr = [
            'username' => $user->username,
            'password' => $user->password,
            'fullname' => $user->fullname,
            'email' => $user->email,
        ];

        $req = HttpClient::getRequest(HttpClient::POST, 'v1/user', [], [], $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);
        return $this->parseResponseBody($body);
    }

    public function updateUser(User $user)
    {
        $bodyArr = [
            'password' => $user->password,
            'fullname' => $user->fullname,
            'email' => $user->email,
        ];
        $uri = sprintf("v1/user/%s", $user->uid);
        $req = HttpClient::getRequest(HttpClient::PUT, $uri, [], [], $bodyArr);
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
            throw new \Exception("both username and email is emtpy");
        }

        $bodyArr = [
            'type' => $type,
            'account' => $account,
            'password' => $user->password,
        ];

        $uri = 'v1/login';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], [], $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);
        return $this->parseResponseBody($body);
    }

    public function verify($resource, $value)
    {
        $validResource = ['username', 'email'];
        if (!in_array($resource, $validResource)) {
            throw new \Exception("{$resource} is invalid resource name");
        }

        $bodyArr = [
            'resource_name' => $resource,
            'value' => $value,
        ];

        $uri = 'v1/user/verify';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], [], $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);
        return $this->parseResponseBody($body);
    }
}
