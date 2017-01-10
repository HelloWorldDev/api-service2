<?php

namespace Being\Api\Service\User;

use Being\Api\Service\BaseClient;
use Being\Api\Service\UserInterface;

class UserClient extends BaseClient implements UserInterface
{
    const URI_IS_ACCOUNT_EXISTS = 'http://game-server.fameapp.us/v1/game/users/search';
    const URI_LOGIN = 'http://game-server.fameapp.us/v1/game/users/search';

    public function isAccountExists($account)
    {
        $response = $this->buildRequest('GET', self::URI_IS_ACCOUNT_EXISTS)
            ->setQuery(['term' => 'liuyong9'])
            ->send();

        return $this->returnInt($response);
    }

    public function register($account, $password)
    {
        $response = $this->buildRequest('GET', self::URI_LOGIN)->send();

        return $this->returnInt($response);
    }

    public function login($account, $password)
    {
        $response = $this->buildRequest('POST', self::URI_IS_ACCOUNT_EXISTS)
            ->setParam(['account' => $account, 'password' => $password])
            ->send();

        return $this->returnInt($response);
    }

    public function updatePassword($account, $newPassword)
    {

    }
}