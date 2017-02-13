<?php

namespace Omnipay\JDPay\Helpers;

class SignHelper
{
    public static function signWithoutToHex($params, $unSignKeyList)
    {
        ksort($params);
        $sourceSignString = self::signString($params, $unSignKeyList);
        $sha256SourceSignString = hash("sha256", $sourceSignString);
        return RSAHelper::encryptByPrivateKey($sha256SourceSignString);
    }

    public static function sign($params, $unSignKeyList)
    {
        ksort($params);
        $sourceSignString = self::signString($params, $unSignKeyList);
        $sha256SourceSignString = hash("sha256", $sourceSignString);
        return RSAHelper::encryptByPrivateKey($sha256SourceSignString);
    }

    public static function signString($data, $unSignKeyList)
    {
        $linkStr = '';
        $isFirst = true;
        ksort($data);
        foreach ($data as $key => $value) {
            if ($value == null || $value == '') {
                continue;
            }
            if (in_array($key, $unSignKeyList)) {
                continue;
            }
            if (!$isFirst) {
                $linkStr .= '&';
            }
            $linkStr .= $key . '=' . $value;
            if ($isFirst) {
                $isFirst = false;
            }
        }
        return $linkStr;
    }
}
