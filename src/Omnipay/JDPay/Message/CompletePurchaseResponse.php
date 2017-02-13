<?php

namespace Omnipay\JDPay\Message;

class CompletePurchaseResponse extends BaseAbstractResponse
{
    /**
     * @var CompletePurchaseRequest
     */
    protected $request;

    public function getTradeStatus()
    {
        return $this->request->getTradeStatus();
    }

    public function getResponseText()
    {
        if ($this->isSuccessful()) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function isTradeStatusOk()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        return $this->data['is_paid'];
    }

    public function isSuccessful()
    {
        return $this->data['verify_success'];
    }
}
