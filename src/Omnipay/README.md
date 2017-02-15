## Introduction

Clone from [https://github.com/branchzero/omnipay-jdpay](https://github.com/branchzero/omnipay-jdpay)

## Warning

当前只完成PurchaseRequest.php文件, 只负责生成合法的表单的数据.

## Usage

```
// 输出提交页面
$gateway = new MobileGateway();
$parameters = [
    'desKey' => 'xxx',
    'public_key_path' => 'xxx',
    'private_key_path' => 'xxx',
    'merchant' => 'xxx',
    'device' => '111',
    'tradeNum' => 'nihao' . time(),
    'tradeTime' => date('YmdHis'),// '20170214060347',
    'tradeName' => '商品1111',
    'tradeDesc' => '交易描述',
    'amount' => '1',
    'currency' => 'CNY',
    'note' => '备注',
    'callbackUrl' => 'your url',
    'notifyUrl' => 'your url',
    'ip' => '',
    'userType' => '',
    'userId' => '',
    'expireTime' => '',
    'industryCategoryCode' => '',
    'orderType' => '1',
    'specCardNo' => '',
    'specId' => '',
    'specName' => '',
];

$purchase = $gateway->purchase($parameters);

echo $purchase->getSubmitHtml();

// 验证回调
VerifyUtils::verifyPurchaseCallback($_GET)

// 验证异步通知
VerifyUtils::verifyPurchaseNotify(file_get_contents('php://input'), 'your desKey', $data);
```