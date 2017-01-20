<?php

namespace Being\Api\Service\User;


class ThirdpartyUser
{
    public $uid;
    public $type;
    public $unionid;
    public $tpname;

    public function __construct($uid, $type, $unionid, $tpname)
    {
        $this->uid = $uid;
        $this->type = $type;
        $this->unionid = $unionid;
        $this->tpname = $tpname;
    }
}