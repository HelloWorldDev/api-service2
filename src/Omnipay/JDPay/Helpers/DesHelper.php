<?php

namespace Omnipay\JDPay\Helpers;

class DesHelper
{
    public static function encrypt($input, $key)
    {
        $key = self::pad2Length(base64_decode($key), 8);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $size = mcrypt_get_block_size('des', 'ecb');
        $input = self::pkcs5Pad($input, $size);
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    public static function decrypt($encrypted, $key)
    {
        $encrypted = base64_decode($encrypted);
        $key = base64_decode($key);
        $key = self::pad2Length($key, 8);
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y = self::pkcs5Unpad($decrypted);

        return $y;
    }

    public static function pad2Length($text, $padlen)
    {
        $len = strlen($text) % $padlen;
        $res = $text;
        $span = $padlen - $len;
        for ($i = 0; $i < $span; $i ++) {
            $res .= chr($span);
        }

        return $res;
    }

    public static function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);

        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs5Unpad($text)
    {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }
}
