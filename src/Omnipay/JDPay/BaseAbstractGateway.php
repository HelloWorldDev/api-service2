<?php

namespace Omnipay\JDPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class BaseAbstractGateway
 * @package Omnipay\JDPay
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */

abstract class BaseAbstractGateway extends AbstractGateway
{
    protected function getVersion()
    {
        return '';
    }

    protected function getTradeType()
    {
        return '';
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

    /**
     * @param array $parameters
     * @return \Omnipay\JDPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array ())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array ())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\CompletePurchaseRequest', $parameters);
    }

    public function notify($parameters = array ())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\NotifyRequest', $parameters);
    }

    public function query($parameters = array ())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\QueryRequest', $parameters);
    }

    public function refund(array $parameters = array ())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\RefundRequest', $parameters);
    }
}
