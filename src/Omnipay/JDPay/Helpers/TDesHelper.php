<?php

namespace Omnipay\JDPay\Helpers;

class TDesHelper
{
    public static function encrypt2HexStr($keys, $sourceData)
    {
        $source = ByteHelper::getBytes($sourceData);
        $merchantData = count($source);
        $x = ($merchantData + 4) % 8;
        $y = ($x == 0) ? 0 : (8 - $x);
        $sizeByte = ByteHelper::integerToBytes($merchantData);
        $resultByte = array();
        for ($i = 0; $i < 4; $i ++) {
            $resultByte [$i] = $sizeByte [$i];
        }
        for ($j = 0; $j < $merchantData; $j ++) {
            $resultByte [4 + $j] = $source [$j];
        }
        for ($k = 0; $k < $y; $k ++) {
            $resultByte [$merchantData + 4 + $k] = 0x00;
        }
        $desdata = self::encrypt(ByteHelper::toStr($resultByte), $keys);

        return ByteHelper::strToHex($desdata);
    }

    public static function decrypt4HexStr($keys, $data)
    {
        $hexSourceData = ByteHelper::hexStrToBytes($data);
        $unDesResult = self::decrypt(ByteHelper::toStr($hexSourceData), $keys);
        $unDesResultByte = ByteHelper::getBytes($unDesResult);
        $dataSizeByte = array();
        for ($i = 0; $i < 4; $i ++) {
            $dataSizeByte [$i] = $unDesResultByte [$i];
        }
        $dsb = ByteHelper::byteArrayToInt($dataSizeByte, 0);
        $tempData = array();
        for ($j = 0; $j < $dsb; $j++) {
            $tempData [$j] = $unDesResultByte [4 + $j];
        }

        return ByteHelper::hexTobin(ByteHelper::bytesToHex($tempData));
    }

    private static function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);

        return $text . str_repeat(chr($pad), $pad);
    }

    private static function pkcs5Unpad($text)
    {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }

        return substr($text, 0, - 1 * $pad);
    }

    public static function encrypt($input, $key)
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        mcrypt_get_block_size('des', 'ecb');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $data;
    }

    public static function decrypt($encrypted, $key)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $decrypted;
    }
    
    public static function padding($str)
    {
        $len = 8 - strlen($str) % 8;
        for ($i = 0; $i < $len; $i ++) {
            $str .= chr(0);
        }

        return $str;
    }

    public static function removePadding($str)
    {
        $len = strlen($str);
        $newstr = '';
        $str = str_split($str);
        for ($i = 0; $i < $len; $i ++) {
            if ($str [$i] != chr(0)) {
                $newstr .= $str [$i];
            }
        }

        return $newstr;
    }

    public static function removeBr($str)
    {
        $len = strlen($str);
        $newstr = '';
        $str = str_split($str);
        for ($i = 0; $i < $len; $i ++) {
            if ($str [$i] != '\n' and $str [$i] != '\r') {
                $newstr .= $str [$i];
            }
        }
        
        return $newstr;
    }
}
