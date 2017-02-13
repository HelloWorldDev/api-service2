<?php

namespace Omnipay\JDPay\Message;

use Omnipay\JDPay\Helpers\TDesHelper;
use Omnipay\JDPay\Helpers\RSAHelper;

class RefundRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://m.jdpay.com/wepay/refund';
    public $sendRefundResponse;

    protected function validateData()
    {
        $this->validate(
            'des_key',
            'mch_id',
            'trade_num',
            'o_trade_num',
            'trade_amount',
            'trade_date',
            'trade_time',
            'trade_notice',
            'trade_note'
        );
    }

    public function getData()
    {
        $this->validateData();

        $trade_data = $this->getTradeData(json_encode(array(
            'tradeNum'      => $this->getTradeNum(),
            'oTradeNum'     => $this->getOTradeNum(),
            'tradeAmount'   => $this->getTradeAmount(),
            'tradeCurrency' => $this->getCurrency(),
            'tradeDate'     => $this->getTradeDate(),
            'tradeTime'     => $this->getTradeTime(),
            'tradeNotice'   => $this->getTradeNotice(),
            'tradeNote'     => $this->getTradeNote(),
        )));

        $data = array(
            'version'      => $this->getVersion(),
            'merchantNum'  => $this->getMchId(),
            'merchantSign' => $this->getMerchantSign($trade_data),
            'data'         => $trade_data,
        );

        return $data;
    }

    public function sendData($data)
    {
        $this->sendRefundResponse = $this->getSendRefundResponse($data);
        return $this->response = new RefundResponse($this, $data);
    }

    protected function getSendRefundResponse($data)
    {
        $result = $this->post($this->getEndpoint(), json_encode($data));

        return json_decode(str_replace("\n", '', $result), true);
    }

    public function getOTradeNum()
    {
        return $this->getParameter('o_trade_num');
    }

    public function setOTradeNum($o_trade_num)
    {
        return $this->setParameter('o_trade_num', $o_trade_num);
    }

    public function getTradeDate()
    {
        return $this->getParameter('trade_date');
    }

    public function setTradeDate($trade_date)
    {
        return $this->setParameter('trade_date', $trade_date);
    }

    public function getTradeTime()
    {
        return $this->getParameter('trade_time');
    }

    public function setTradeTime($trade_time)
    {
        return $this->setParameter('trade_time', $trade_time);
    }

    public function getTradeNotice()
    {
        return $this->getParameter('trade_notice');
    }

    public function setTradeNotice($trade_notice)
    {
        return $this->setParameter('trade_notice', $trade_notice);
    }

    public function getTradeNote()
    {
        return $this->getParameter('trade_note');
    }

    public function setTradeNote($trade_note)
    {
        return $this->setParameter('trade_note', $trade_note);
    }

    public function getTradeData($trade_json)
    {
        return TDesHelper::encrypt2HexStr(base64_decode($this->getDesKey()), $trade_json);
    }

    public function getMerchantSign($trade_data)
    {
        return RSAHelper::encryptByPrivateKey(hash("sha256", $trade_data));
    }
}
