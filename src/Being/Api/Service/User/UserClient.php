<?php

namespace Being\Api\Service\User;

use Being\Api\Service\BaseClient;
use Being\Api\Service\HttpClient;

class UserClient extends BaseClient implements ClientInterface
{
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
        foreach (User::UPDATE_ATTRIBUTES as $key) {
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

    public function updatePassword($id, $oldPassword, $newPassword)
    {
        $bodyArr = [
            'id' => $id,
            'old_password' => $oldPassword,
            'password' => $newPassword,
        ];
        $header = $this->getSecretHeader();
        $bodyArr += $this->getSecretData();
        $uri = 'v1/user/password';
        $req = HttpClient::getRequest(HttpClient::POST, $uri, [], $header, $bodyArr);
        list($code, $body, $header) = $this->httpClient->send($req);

        return $this->parseResponseBody($body);
    }
}
