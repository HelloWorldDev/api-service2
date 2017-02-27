<?php

namespace Omnipay\JDFastPay;

use DES;

class PayService
{
    function trade($data_xml, $config)
    {
        $desObj = new DES($config->des);
        $dataDES = $desObj->encrypt($data_xml);
        $sign = myMd5($config->version . $config->merchant . $config->terminal . $dataDES, $config->md5);
        $xml = xml_create($config->version, $config->merchant, $config->terminal, $dataDES, $sign);
        //使用方法
        $param = 'charset=UTF-8&req=' . urlencode(base64_encode($xml));
        $resp = $this->post($param);

        return $resp;
    }

    function post($param)
    {
        $url = "https://quick.chinabank.com.cn/express.htm";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $file_contents = curl_exec($ch);
        curl_close($ch);

        return $file_contents;
    }
}