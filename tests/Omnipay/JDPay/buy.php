<?php
use Omnipay\JDPay\Helpers\ConfigUtil;
use Omnipay\JDPay\MobileGateway;

require __DIR__ . '/common.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <title>"京东支付"H5版demo</title>
</head>
<body onload="autosubmit()">
<form action="<?=$payUrl?>"  method="post" id="batchForm" >
    <input type="text" name="version" value="<?=$data['version']?>"/><br/>
    <input type="text" name="merchant" value="<?=$data['merchant']?>"/><br/>
    <input type="text" name="device" value="<?=$data['device']?>"/><br/>
    <input type="text" name="tradeNum" value="<?=$data['tradeNum']?>"/><br/>
    <input type="text" name="tradeName" value="<?=$data['tradeName']?>"/><br/>
    <input type="text" name="tradeDesc" value="<?=$data['tradeDesc']?>"/><br/>
    <input type="text" name="tradeTime" value="<?=$data['tradeTime']?>"/><br/>
    <input type="text" name="amount" value="<?=$data['amount']?>"/><br/>
    <input type="text" name="currency" value="<?=$data['currency']?>"/><br/>
    <input type="text" name="note" value="<?=$data['note']?>"/><br/>
    <input type="text" name="callbackUrl" value="<?=$data['callbackUrl']?>"/><br/>
    <input type="text" name="notifyUrl" value="<?=$data['notifyUrl']?>"/><br/>
    <input type="text" name="ip" value="<?=$data['ip']?>"/><br/>
    <input type="text" name="userType" value="<?=$data['userType']?>"/><br/>
    <input type="text" name="userId" value="<?=$data['userId']?>"/><br/>
    <input type="text" name="expireTime" value="<?=$data['expireTime']?>"/><br/>
    <input type="text" name="orderType" value="<?=$data['orderType']?>"/><br/>
    <input type="text" name="industryCategoryCode" value="<?=$data['industryCategoryCode']?>"/><br/>
    <input type="text" name="specCardNo" value="<?=$data['specCardNo']?>"/><br/>
    <input type="text" name="specId" value="<?=$data['specId']?>"/><br/>
    <input type="text" name="specName" value="<?=$data['specName']?>"/><br/>
    <input type="text" name="sign" value="<?=$data['sign']?>"/><br/>
</form>
<script>
    function autosubmit(){
        document.getElementById("batchForm").submit();
    }
</script>

</body>
</html>