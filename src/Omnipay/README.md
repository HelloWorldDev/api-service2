## Introduction

Clone from [https://github.com/branchzero/omnipay-jdpay](https://github.com/branchzero/omnipay-jdpay)

## Warning

当前只完成PurchaseRequest.php文件, 只负责生成合法的表单的数据.

## Usage

```
ConfigUtil::setConfigFile(__DIR__ . '/config/config.ini');
RSAHelper::setPrivateKey(__DIR__ . '/config/seller_rsa_private_key.pem');
RSAHelper::setPublicKey(__DIR__ . '/config/wy_rsa_public_key.pem');
$gateway = new MobileGateway();
$purchase = $gateway->purchase([
    'merchant' => ConfigUtil::get_val_by_key('merchantNum'),
    'device' => '111',
    'tradeNum' => time(),
    'tradeName' => '商品1111',
    'tradeDesc' => '商品描述',
    'tradeTime' => date('YmdHis'),
    'amount' => 1,
    'currency' => 'CNY',
    'note' => '',
    'callbackUrl' => ConfigUtil::get_val_by_key('callbackUrl'),
    'notifyUrl' => ConfigUtil::get_val_by_key('notifyUrl'),
    'ip' => '',
    'specCardNo' => '',
    'specId' => '',
    'specName' => '',
    'userType' => '',
    'userId' => '',
    'expireTime' => '',
    'orderType' => 1, // 虚拟
    'industryCategoryCode' => '',
]);
$payUrl = $purchase->getEndpoint();
$data = $purchase->getData();
```