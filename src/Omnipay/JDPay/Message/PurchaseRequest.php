<?php

namespace Omnipay\JDPay\Message;

use Omnipay\JDPay\Helpers\DesHelper;
use Omnipay\JDPay\Helpers\SignHelper;

class PurchaseRequest extends BaseAbstractRequest
{
    protected $endpoint = array(
        'web'    => 'https://wepay.jd.com/jdpay/saveOrder',
        'mobile' => 'https://h5pay.jd.com/jdpay/saveOrder',
    );

    public function getEndpoint()
    {
        return $this->endpoint[$this->getTradeType()];
    }

    protected function validateData()
    {
        $this->validate(
            'version',
            'merchant',
            'tradeNum',
            'tradeName',
            'tradeTime',
            'amount',
            'orderType',
            'currency',
            'callbackUrl',
            'notifyUrl'
        );
    }

    public function getData()
    {
        $this->validateData();
        $keys = [
            // 'version',
            // 'merchant',
            'device',
            'tradeNum',
            'tradeName',
            'tradeDesc',
            'tradeTime',
            'amount',
            'currency',
            'note',
            'callbackUrl',
            'notifyUrl',
            'ip',
            'specCardNo',
            'specId',
            'specName',
            'userType',
            'userId',
            'expireTime',
            'orderType',
            'industryCategoryCode',
        ];

        $data['version'] = $this->getVersion();
        $data['merchant'] = $this->getParameter('merchant');
        foreach($keys as $key) {
            $val = $this->getParameter($key);
            if(!is_null($val)) {
                $data[$key] = $val;
            }
        }
        $data['sign'] = SignHelper::signWithoutToHex($data, ['sign']);

        $desKey = $this->getDesKey();
        foreach($keys as $key) {
            $val = $this->getParameter($key);
            if(!is_null($val) && $val !== '') {
                $data[$key] = DesHelper::encrypt($val, $desKey);
            }
        }
        
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
