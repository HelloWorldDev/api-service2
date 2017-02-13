<?php

namespace Omnipay\JDPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{
    protected $endpoint;

    public function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 28);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length:' . strlen($data)
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getCurrency()
    {
        return 'CNY';
    }

    public function getIp()
    {
        return $this->httpRequest->getClientIp();
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setDesKey($desKey)
    {
        $this->setParameter('des_key', $desKey);
    }

    public function getDesKey()
    {
        return $this->getParameter('des_key');
    }

    public function getPublicKeyPath()
    {
        return $this->getParameter('public_key_path');
    }

    public function setPublicKeyPath($keyPath)
    {
        if (!is_file($keyPath)) {
            throw new InvalidRequestException("The public_key_path($keyPath) is not exists");
        }
        $this->setParameter('public_key_path', $keyPath);
    }

    public function getPrivateKeyPath()
    {
        return $this->getParameter('private_key_path');
    }

    public function setPrivateKeyPath($keyPath)
    {
        if (!is_file($keyPath)) {
            throw new InvalidRequestException("The private_key_path($keyPath) is not exists");
        }
        $this->setParameter('private_key_path', $keyPath);
    }

    public function getTradeType()
    {
        return $this->getParameter('trade_type');
    }
}
