<?php

namespace Omnipay\JDPay\Utils;

abstract class VerifyUtils
{
    public static function verifyPurchaseCallback($param)
    {
        $sign = $param['sign'];
        unset($param['sign']);

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
