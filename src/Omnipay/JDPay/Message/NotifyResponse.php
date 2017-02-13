<?php

namespace Omnipay\JDPay\Message;

class NotifyResponse extends BaseAbstractResponse
{
    public function getOrderData()
    {
        return $this->isSuccessful() ? $this->data['order_data'] : array();
    }

    public function isSuccessful()
    {
        return $this->data['verify_success'];
    }
}
