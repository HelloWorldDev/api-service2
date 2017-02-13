<?php

namespace Omnipay\JDPay\Helpers;

class RSAHelper
{
    protected static $privateKey;
    protected static $publicKey;

    public static function setPrivateKey($privateKey)
    {
        self::$privateKey = $privateKey;
    }

    public static function setPublicKey($publicKey)
    {
        self::$publicKey = $publicKey;
    }

    public static function encryptByPrivateKey($data)
    {
        $pi_key =  openssl_pkey_get_private(file_get_contents(self::$privateKey));
        $encrypted='';
        openssl_private_encrypt($data, $encrypted, $pi_key, OPENSSL_PKCS1_PADDING);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }
    
    public static function decryptByPublicKey($data)
    {
        $pu_key =  openssl_pkey_get_public(file_get_contents(self::$publicKey));
        $decrypted = '';
        $data = base64_decode($data);
        openssl_public_decrypt($data, $decrypted, $pu_key);

        return $decrypted;
    }
}
