<?php

namespace Omnipay\JDPay\Message;

use Omnipay\JDPay\Helpers\TDesHelper;
use Omnipay\JDPay\Helpers\RSAHelper;

class QueryRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://m.jdpay.com/wepay/query';
    public $sendQueryResponse;

    protected function validateData()
    {
        $this->validate(
            'des_key',
            'mch_id',
            'trade_num'
        );
    }

    public function getData()
    {
        $this->validateData();

        $trade_data = $this->getTradeData(json_encode(array('tradeNum' => $this->getTradeNum())));
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
        $this->sendQueryResponse = $this->getSendQueryResponse($data);
        return $this->response = new QueryResponse($this, $data);
    }

    protected function getSendQueryResponse($data)
    {
        $result = $this->post($this->getEndpoint(), json_encode($data));

        return json_decode(str_replace("\n", '', $result), true);
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
