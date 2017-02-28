<?php

namespace Omnipay\JDPay;

use Omnipay\Common\AbstractGateway;

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
    abstract public function getVersion();
    abstract public function getTradeType();

    // Fix integer parameter DES failed
    protected function wrapperParameters($parameters)
    {
        return array_map(function ($v) {
            return (string)$v;
        }, $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\JDPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        $parameters['trade_type'] = $this->getTradeType();
        $parameters['version'] = $this->getVersion();
        return $this->createRequest('\Omnipay\JDPay\Message\PurchaseRequest', $this->wrapperParameters($parameters));
    }

    public function completePurchase(array $parameters = array ())
    {
        return null;
    }

    public function notify($parameters = array ())
    {
        return null;
    }

    public function query($parameters = array ())
    {
        return null;
    }

    public function refund(array $parameters = array ())
    {
        return null;
    }
}
