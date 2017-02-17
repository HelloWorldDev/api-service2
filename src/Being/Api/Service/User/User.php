<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 3:24 PM
 */

namespace Being\Api\Service\User;

/**
 * Class User
 * @package Being\Api\Service\User
 * @property $uid
 * @property $username
 * @property $fullname
 * @property $password
 * @property $email
 * @property $avatar
 * @property $mobile
 * @property $gender
 * @property $age
 */
class User
{
    protected $uid;
    protected $username;
    protected $fullname;
    protected $password;
    protected $email;
    protected $avatar;
    protected $mobile;
    protected $gender;
    protected $age;

    const UPDATE_ATTRIBUTES = ['fullname', 'password', 'email', 'avatar', 'mobile', 'gender', 'age'];

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
        $o = new static(0, '', '', '', '', '');
        foreach ($attributes as $k => $v) {
            $o->$k = $v;
        }

        return $o;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
