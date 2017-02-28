<?php

namespace Omnipay\JDFastPay;

class Config
{
    //版本号
    public $version='1.0.0';
    //终端号
    public $terminal= '00000001';
    //商户号
    public $merchant='';
    //DES密钥
    public $des = '';
    //md5密钥
    public $md5='';

    public static function create($attributes)
    {
        $o = new static();
        foreach ($attributes as $key => $val) {
            $o->$key = $val;
        }

        return $o;
    }
}