<?php

use Omnipay\JDPay\Utils\RSAUtils;
use Omnipay\JDPay\Utils\SignUtil;
use Omnipay\JDPay\Utils\VerifyUtils;

require __DIR__ . '/common.php';

jdpay_log('callback');
jdpay_log($_POST);
jdpay_log($_GET);
jdpay_log(file_get_contents('php://input'));

$returnJson = '{"tradeNum":"03565f8a7ad573fa6f4a1fa03005b893311b168522243196","amount":"e5a6c3761ab9ddaf","currency":"ac7132c57f10d3ce","tradeTime":"d9668085c69c2ecb3bc29671c3c711f1b102cc901c892d65","status":"e00c693e6c5b8a60","note":"5b8119fe85e1f5ba93648010c439bcb6","sign":"hC5rxSkLyeHxZb2pj+rjZWYVq2fJOnwniRFbDkwpJRI5k4jS/eJNUFQjnAkNw5UyyOc24o4zEcgN\nABWc5/zMaNZFFgirkNOSQzRYfiOzeecbCDVdDptktcOJY734V8y5kEYUJKb6LDekyDGr9a2gJmgV\n2MFV77pN6r30MRC7CX8=\n"}';
$param = json_decode($returnJson, true);
$flag = VerifyUtils::verifyPurchaseCallback($param);

print_r($param);
var_dump($flag);
