<?php

namespace Being\Api\Service\Thirdparty;

class ThirdpartyAuth
{
    public $uid;
    public $type;
    public $unionid;
    public $tpname;

    const TYPE_WETHAT = 1;
    const TYPE_FACEBOOK = 2;
    const TYPE_WEIBO = 3;
    const TYPE_QQ = 4;

    public function __construct($uid, $type, $unionid, $tpname)
    {
        $this->uid = $uid;
        $this->type = $type;
        $this->unionid = $unionid;
        $this->tpname = $tpname;
    }

    public static function CheckType($type)
    {
        return ($type == self::TYPE_WETHAT
            || $type == self::TYPE_FACEBOOK
            || $type == self::TYPE_WEIBO
            || $type == self::TYPE_QQ);
    }
}