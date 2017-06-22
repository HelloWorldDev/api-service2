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
    public $mobile;
    public $gender;
    public $age;

    const UPDATE_ATTRIBUTES = ['username', 'fullname', 'password', 'email', 'avatar', 'mobile', 'gender', 'age'];

    /**
     * @deprecated use User::create() instead
     * User constructor.
     * @param $uid
     * @param $username
     * @param $fullname
     * @param $password
     * @param $email
     * @param $avatar
     */
    public function __construct($uid, $username, $fullname, $password, $email, $avatar)
    {
        $this->uid = $uid;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->password = $password;
        $this->email = $email;
        $this->avatar = $avatar;
    }

    public static function create(array $attributes)
    {
        $o = new static(null, null, null, null, null, null);
        foreach ($attributes as $k => $v) {
            if (property_exists(User::class, $k)) {
                $o->$k = $v;
            }
        }

        return $o;
    }
}
