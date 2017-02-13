<?php

namespace Omnipay\JDPay\Message;

use Omnipay\JDPay\Helpers\TDesHelper;
use Omnipay\JDPay\Helpers\RSAHelper;

class RefundResponse extends BaseAbstractResponse
{
    /**
     * @var RefundRequest
     */
    protected $request;
    protected $refund_data;

    public function isSuccessful()
    {
        $result = $this->request->sendRefundResponse;
        if ($result['resultCode'] == 0) {
            $result = $result['resultData'];
            if ($result != null) {
                $data = $result["data"];
                $sign = $result["sign"];
                $decryptStr = RSAHelper::decryptByPublicKey($sign);
                $sha256SourceSignString = hash("sha256", $data);
                if ($decryptStr == $sha256SourceSignString) {
                    $decrypData = TDesHelper::decrypt4HexStr(base64_decode($this->request->getDesKey()), $data);
                    $decrypData = json_decode($decrypData, true);
                    if ($decrypData != null) {
                        $this->refund_data = $decrypData;
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getOrderData()
    {
        return $this->isSuccessful() ? $this->refund_data : array();
    }
}
