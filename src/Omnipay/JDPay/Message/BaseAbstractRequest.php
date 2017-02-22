<?php

namespace Omnipay\JDPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\JDPay\Utils\RSAUtils;

abstract class BaseAbstractRequest extends AbstractRequest
{
    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($version)
    {
        $this->setParameter('version', $version);
    }

    public function getTradeType()
    {
        return $this->getParameter('trade_type');
    }

    public function setTradeType($tradeType)
    {
        $this->setParameter('trade_type', $tradeType);
    }
    
    public function getDesKey()
    {
        $desKey = $this->getParameter('des_key');

        return base64_decode($desKey);
    }

    public function setDesKey($desKey)
    {
        $this->setParameter('des_key', $desKey);
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

    public function setKeysPath()
    {
        if ($file = $this->getPrivateKeyPath()) {
            RSAUtils::setPrivateKey($file);
        }

        if ($file = $this->getPublicKeyPath()) {
            RSAUtils::setPublicKey($file);
        }
    }
}
