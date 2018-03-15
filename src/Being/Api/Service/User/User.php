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
    public $name;
    public $country;
    public $country_code;
    public $city;
    public $city_code;
    public $birthday;
    public $id_type;
    public $id_number;
    public $id_card_pic1;
    public $id_card_pic2;
    public $id_card_pic3;
    public $auth_status;

    const UPDATE_ATTRIBUTES = ['username', 'fullname', 'password', 'email', 'avatar', 'mobile', 'gender', 'age', 'name', 'country', 'country_code', 'city', 'city_code','birthday', 'id_type', 'id_number', 'id_card_pic1', 'id_card_pic2', 'id_card_pic3', 'auth_status'];

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
