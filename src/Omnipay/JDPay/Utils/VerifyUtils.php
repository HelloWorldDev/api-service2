<?php

namespace Omnipay\JDPay\Utils;

abstract class VerifyUtils
{
    public static function verifyPurchaseCallback($param, $desKey, &$returnData)
    {
        $desKey = base64_decode($desKey);
        
        $sign = $param['sign'];
        unset($param['sign']);

        $returnData = $param;
        foreach ($returnData as &$v) {
            $v = TDESUtil::decrypt4HexStr($desKey, $v);
        }

        $strSourceData = SignUtil::signString($param, []);
        $decryptStr = RSAUtils::decryptByPublicKey($sign);
        $sha256SourceSignString = hash('sha256', $strSourceData);
        $ret = $decryptStr == '' || $decryptStr == $sha256SourceSignString;

        return $ret;
    }

    public static function verifyPurchaseNotify($notifyXmlData, $desKey, &$returnData)
    {
        $flag = XMLUtil::decryptResXml($notifyXmlData, $desKey, $returnData);

        return $flag;
    }
}
