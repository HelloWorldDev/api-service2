<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 3:11 PM
 */

namespace Being\Api\Service\User;

use Being\Api\Service\Thirdparty\ThirdpartyAuth;

interface ClientInterface
{
    public function register(User $user);
    public function updateUser(User $user);
    public function login(User $user);
    public function verify(User $user);
    public function find3user(ThirdpartyAuth $ta);
    public function register3user(ThirdpartyAuth $ta, User $user);
    public function login3user($unionid, $code, $type, $config);
    public function find(User $user);
    public function updatePassword($id, $oldPassword, $newPassword);
}
