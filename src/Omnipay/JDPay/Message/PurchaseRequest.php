<?php

namespace Omnipay\JDPay\Message;

use Omnipay\JDPay\Utils\SignUtil;
use Omnipay\JDPay\Utils\TDESUtil;

class PurchaseRequest extends BaseAbstractRequest
{
    protected $endpoint = array(
        'web'    => 'https://wepay.jd.com/jdpay/saveOrder',
        'mobile' => 'https://h5pay.jd.com/jdpay/saveOrder',
    );

    public function getEndpoint()
    {
        return $this->endpoint[$this->getTradeType()];
    }

    protected function validateData()
    {
        $this->validate(
            'version',
            'merchant',
            'tradeNum',
            'tradeName',
            'tradeTime',
            'amount',
            'orderType',
            'currency',
            'callbackUrl',
            'notifyUrl'
        );
    }

    public function getData()
    {
        $this->validateData();
        $this->setKeysPath();

        $keys = [
            // 'version',
            // 'merchant',
            'device',
            'tradeNum',
            'tradeName',
            'tradeDesc',
            'tradeTime',
            'amount',
            'currency',
            'note',
            'callbackUrl',
            'notifyUrl',
            'ip',
            'specCardNo',
            'specId',
            'specName',
            'userType',
            'userId',
            'expireTime',
            'orderType',
            'industryCategoryCode',
        ];

        $param['version'] = $this->getParameter('version');
        $param['merchant'] = $this->getParameter('merchant');
        foreach($keys as $key) {
            $param[$key] = $this->getParameter($key);
        }

        $param["sign"] = SignUtil::signWithoutToHex($param, []);
        $desKey = $this->getDesKey();

        foreach($keys as $key) {
            $val = $this->getParameter($key);
            if(!is_null($val) && $val !== '') {
                $param[$key] = TDESUtil::encrypt2HexStr($desKey, $val);
            }
        }
        
        return $param;
    }

    public function sendData($data)
    {
        return $this->response = null;
    }

    public function getSubmitHtml()
    {
        $payUrl = $this->getEndpoint();
        $data = $this->getData();

        return <<<EOF
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <title>京东支付</title>
</head>
<body onload="autosubmit()">
<img style="position: absolute; top: 50%; left: 50%; width: 24px; height: 24px;margin: -12px 0 0 -12px;"
        src="data:image/gif;base64,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" />
<form action="{$payUrl}"  method="post" id="batchForm" style="visibility: hidden" >
    <input type="text" name="version" value="{$data['version']}"/><br/>
    <input type="text" name="merchant" value="{$data['merchant']}"/><br/>
    <input type="text" name="device" value="{$data['device']}"/><br/>
    <input type="text" name="tradeNum" value="{$data['tradeNum']}"/><br/>
    <input type="text" name="tradeName" value="{$data['tradeName']}"/><br/>
    <input type="text" name="tradeDesc" value="{$data['tradeDesc']}"/><br/>
    <input type="text" name="tradeTime" value="{$data['tradeTime']}"/><br/>
    <input type="text" name="amount" value="{$data['amount']}"/><br/>
    <input type="text" name="currency" value="{$data['currency']}"/><br/>
    <input type="text" name="note" value="{$data['note']}"/><br/>
    <input type="text" name="callbackUrl" value="{$data['callbackUrl']}"/><br/>
    <input type="text" name="notifyUrl" value="{$data['notifyUrl']}"/><br/>
    <input type="text" name="ip" value="{$data['ip']}"/><br/>
    <input type="text" name="userType" value="{$data['userType']}"/><br/>
    <input type="text" name="userId" value="{$data['userId']}"/><br/>
    <input type="text" name="expireTime" value="{$data['expireTime']}"/><br/>
    <input type="text" name="orderType" value="{$data['orderType']}"/><br/>
    <input type="text" name="industryCategoryCode" value="{$data['industryCategoryCode']}"/><br/>
    <input type="text" name="specCardNo" value="{$data['specCardNo']}"/><br/>
    <input type="text" name="specId" value="{$data['specId']}"/><br/>
    <input type="text" name="specName" value="{$data['specName']}"/><br/>
    <input type="text" name="sign" value="{$data['sign']}"/><br/>
</form>
<script>
    function autosubmit(){
        document.getElementById("batchForm").submit();
    }
</script>

</body>
</html>
EOF;
    }

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    /**
     * @param mixed $merchant
     */
    public function setMerchant($merchant)
    {
        $this->setParameter('merchant', $merchant);
    }

    /**
     * @return mixed
     */
    public function getDevice()
    {
        return $this->getParameter('device');
    }

    /**
     * @param mixed $device
     */
    public function setDevice($device)
    {
        $this->setParameter('device', $device);
    }

    /**
     * @return mixed
     */
    public function getTradeNum()
    {
        return $this->getParameter('tradeNum');
    }

    /**
     * @param mixed $tradeNum
     */
    public function setTradeNum($tradeNum)
    {
        $this->setParameter('tradeNum', $tradeNum);
    }

    /**
     * @return mixed
     */
    public function getTradeName()
    {
        return $this->getParameter('tradeName');
    }

    /**
     * @param mixed $tradeName
     */
    public function setTradeName($tradeName)
    {
        $this->setParameter('tradeName', $tradeName);
    }

    /**
     * @return mixed
     */
    public function getTradeDesc()
    {
        return $this->getParameter('tradeDesc');
    }

    /**
     * @param mixed $tradeDesc
     */
    public function setTradeDesc($tradeDesc)
    {
        $this->setParameter('tradeDesc', $tradeDesc);
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }


    public function setAmount($amount)
    {
        $this->setParameter('amount', $amount);
    }

    /**
     * @return mixed
     */
    public function getTradeTime()
    {
        return $this->getParameter('tradeTime');
    }

    /**
     * @param mixed $tradeTime
     */
    public function setTradeTime($tradeTime)
    {
        $this->setParameter('tradeTime', $tradeTime);
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($currency)
    {
        $this->setParameter('currency', $currency);
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->getParameter('note');
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->setParameter('note', $note);
    }

    /**
     * @return mixed
     */
    public function getCallbackUrl()
    {
        return $this->getParameter('callbackUrl');
    }

    /**
     * @param mixed $callbackUrl
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->setParameter('callbackUrl', $callbackUrl);
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->setParameter('notifyUrl', $notifyUrl);
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->getParameter('ip');
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->setParameter('ip', $ip);
    }

    /**
     * @return mixed
     */
    public function getSpecCardNo()
    {
        return $this->getParameter('specCardNo');
    }

    /**
     * @param mixed $specCardNo
     */
    public function setSpecCardNo($specCardNo)
    {
        $this->setParameter('specCardNo', $specCardNo);
    }

    /**
     * @return mixed
     */
    public function getSpecId()
    {
        return $this->getParameter('specId');
    }

    /**
     * @param mixed $specId
     */
    public function setSpecId($specId)
    {
        $this->setParameter('specId', $specId);
    }

    /**
     * @return mixed
     */
    public function getSpecName()
    {
        return $this->getParameter('specName');
    }

    /**
     * @param mixed $specName
     */
    public function setSpecName($specName)
    {
        $this->setParameter('specName', $specName);
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->getParameter('userType');
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->setParameter('userType', $userType);
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->getParameter('userId');
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->setParameter('userId', $userId);
    }

    /**
     * @return mixed
     */
    public function getExpireTime()
    {
        return $this->getParameter('expireTime');
    }

    /**
     * @param mixed $expireTime
     */
    public function setExpireTime($expireTime)
    {
        $this->setParameter('expireTime', $expireTime);
    }

    /**
     * @return mixed
     */
    public function getOrderType()
    {
        return $this->getParameter('orderType');
    }

    /**
     * @param mixed $orderType
     */
    public function setOrderType($orderType)
    {
        $this->setParameter('orderType', $orderType);
    }

    /**
     * @return mixed
     */
    public function getIndustryCategoryCode()
    {
        return $this->getParameter('industryCategoryCode');
    }

    /**
     * @param mixed $industryCategoryCode
     */
    public function setIndustryCategoryCode($industryCategoryCode)
    {
        $this->setParameter('industryCategoryCode', $industryCategoryCode);
    }
}
