<?php

namespace Omnipay\JDPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\JDPay\Helpers\DesHelper;

class NotifyRequest extends BaseAbstractRequest
{
    public function getData()
    {
        $this->validateRequestParams('resp');
        $this->validate('md5_key', 'des_key');
        return $this->getParameters();
    }

    public function validateRequestParams()
    {
        foreach (func_get_args() as $key) {
            $value = $this->getRequestParam($key);
            if (is_null($value)) {
                throw new InvalidRequestException("The request_params.$key parameter is required");
            }
        }
    }

    public function getRequestParam($key)
    {
        $params = $this->getRequestParams();
        if (isset($params[$key])) {
            return $params[$key];
        } else {
            return null;
        }
    }

    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }

    public function setRequestParams($value)
    {
        return $this->setParameter('request_params', $value);
    }

    public function setRequestParam($key, $val)
    {
        $params = $this->getRequestParams();
        $params[$key] = $val;
        $this->setRequestParams($params);

        return true;
    }

    public function getResp()
    {
        return $this->getRequestParam('resp');
    }

    public function setResp($value)
    {
        return $this->setRequestParam('resp', $value);
    }

    public function XMLToArray($xml)
    {
        $array = (array)simplexml_load_string($xml);
        foreach ($array as $key => $item) {
            $array[$key] = $this->structToArray((array)$item);
        }

        return $array;
    }

    public function structToArray($item)
    {
        if (!is_string($item)) {
            $item = (array)$item;
            foreach ($item as $key => $val) {
                $item[$key] = $this->structToArray($val);
            }
        }

        return $item;
    }

    public function generateSign($data, $md5Key)
    {
        return md5($data['VERSION'][0].$data['MERCHANT'][0].$data['TERMINAL'][0].$data['DATA'][0].$md5Key);
    }

    public function sendData($data)
    {
        if ($data == null) {
            $data['verify_success'] = false;
        }

        $params = $this->XMLToArray(base64_decode($data));
        $ownSign = $this->generateSign($params, $this->getMd5Key());
        $data['verify_success'] = !!($params['SIGN'][0] == $ownSign);
        $data['order_data'] = DesHelper::decrypt($params['DATA'][0], $this->getDesKey());

        return $this->response = new NotifyResponse($this, $data);
    }
}
