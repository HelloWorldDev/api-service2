<?php

namespace Being\Api\Service;

interface UserInterface
{
    public function isAccountExists($account);

    public function register($account, $password);

    public function login($account, $password);

    public function updatePassword($account, $newPassword);
}