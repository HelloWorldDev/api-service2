<?php

require __DIR__ . '/../../../../../../../../vendor/autoload.php';
$config = require __DIR__ . '/../../../../../config/config.php';

use Omnipay\JDPay\Utils\RSAUtils;

error_reporting(0);

RSAUtils::setPrivateKey(__DIR__ . '/../../../../../config/seller_rsa_private_key.pem');
RSAUtils::setPublicKey(__DIR__ . '/../../../../../config/wy_rsa_public_key.pem');

function jdpay_log($data)
{
    $filename = 'jdpay.log';
    $content = sprintf('[%s] %s%s', date('Y-m-d H:i:s'), is_string($data) ? $data : var_export($data, true), "\n");
    file_put_contents(__DIR__ . '/../../logs/' . $filename, $content, FILE_APPEND);
}
