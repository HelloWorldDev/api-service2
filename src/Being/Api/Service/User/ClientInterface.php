<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 3:11 PM
 */

namespace Being\Api\Service\User;

interface ClientInterface
{
    public function register(User $user);
    public function updateUser(User $user);
    public function login(User $user);
    public function verify(User $user);
    public function updatePassword($id, $oldPassword, $newPassword);
}
