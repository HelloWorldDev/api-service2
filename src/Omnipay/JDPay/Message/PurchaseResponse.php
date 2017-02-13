<?php

namespace Omnipay\JDPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class PurchaseResponse
 * @package Omnipay\JDPay\Message
 * @property PurchaseRequest $request
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function isSuccessful()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getReturnUrl();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
