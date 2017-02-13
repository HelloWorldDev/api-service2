<?php

namespace Omnipay\JDPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\JDPay\Helpers\SignHelper;
use Omnipay\JDPay\Helpers\RSAHelper;

class CompletePurchaseRequest extends BaseAbstractRequest
{
    public function getData()
    {
        $this->validateRequestParams('token', 'tradeAmount', 'tradeCurrency', 'tradeDate', 'tradeNum', 'tradeStatus', 'tradeTime', 'sign');
        if ($this->getTradeType() == 'web') {
            $this->validateRequestParams('tradeNote');
        }
        
        return $this->getParameters();
    }

    public function validateRequestParams()
    {
        foreach (func_get_args() as $key) {
            $value = $this->getRequestParam($key);
            if (is_null($value)) {
                throw new InvalidRequestException("The request_params.$key parameter is required");
            }
        }
    }

    public function getRequestParam($key)
    {
        $params = $this->getRequestParams();
        if (isset($params[$key])) {
            return $params[$key];
        } else {
            return null;
        }
    }

    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }

    public function setRequestParams($value)
    {
        return $this->setParameter('request_params', $value);
    }

    public function getToken()
    {
        return $this->getRequestParam('token');
    }

    public function setToken($value)
    {
        return $this->setRequestParam('token', $value);
    }

    public function getTradeAmount()
    {
        return $this->getRequestParam('tradeAmount');
    }

    public function setTradeAmount($value)
    {
        return $this->setRequestParam('tradeAmount', $value);
    }

    public function getTradeCurrency()
    {
        return $this->getRequestParam('tradeCurrency');
    }

    public function setTradeCurrency($value)
    {
        return $this->setRequestParam('tradeCurrency', $value);
    }

    public function getTradeDate()
    {
        return $this->getRequestParam('tradeDate');
    }

    public function setTradeDate($value)
    {
        return $this->setRequestParam('tradeDate', $value);
    }

    public function getTradeNote()
    {
        return $this->getRequestParam('tradeNote');
    }

    public function setTradeNote($value)
    {
        return $this->setRequestParam('tradeNote', $value);
    }

    public function getTradeNum()
    {
        return $this->getRequestParam('tradeNum');
    }

    public function setTradeNum($value)
    {
        return $this->setRequestParam('tradeNum', $value);
    }

    public function getTradeStatus()
    {
        return $this->getRequestParam('tradeStatus');
    }

    public function setTradeStatus($value)
    {
        return $this->setRequestParam('tradeStatus', $value);
    }

    public function getTradeTime()
    {
        return $this->getRequestParam('tradeTime');
    }

    public function setTradeTime($value)
    {
        return $this->setRequestParam('tradeTime', $value);
    }

    public function getSign()
    {
        return $this->getRequestParam('sign');
    }

    public function setSign($value)
    {
        return $this->setRequestParam('sign', $value);
    }

    public function sendData($data)
    {
        ksort($data);
        $data = SignHelper::signString($data, SignHelper::$unSignKeyList);
        $decryptStr = RSAHelper::decryptByPublicKey($this->getSign());
        $sha256SourceSignString = hash("sha256", $data);

        $data['verify_success'] = !!(strcasecmp($decryptStr, $sha256SourceSignString) != 0);
        $data['is_paid']        = $data['verify_success'] && $this->getTradeStatus() == 0;

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
