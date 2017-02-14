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
