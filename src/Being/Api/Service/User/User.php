<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 3:24 PM
 */

namespace Being\Api\Service\User;


class User
{
    public $uid;
    public $username;
    public $fullname;
    public $password;
    public $email;
    public $avatar;

    function __construct($uid, $username, $fullname, $password, $email, $avatar)
    {
        $this->uid = $uid;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->password = $password;
        $this->email = $email;
        $this->avatar = $avatar;
    }
}