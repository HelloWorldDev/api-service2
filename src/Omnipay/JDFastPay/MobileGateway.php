<?php

namespace Omnipay\JDFastPay;

use DES;
use Omnipay\Common\AbstractGateway;

include __DIR__ . '/express-php/des.php';
require_once __DIR__ . '/express-php/xml.php';
require_once __DIR__ . '/express-php/md5.php';

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class MobileGateway extends AbstractGateway
{
    protected $config;

    public function setConfig($config)
    {
        $this->config = Config::create($config);
    }

    public function getName()
    {
        return 'JDFastPay_Mobile';
    }

    function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }

    public function prePurchase($data)
    {
        $card_bank = $data['card_bank']; // 银行编码
        $card_type = $data['card_type']; // 卡类型（信用卡：C借记卡：D）
        $card_no = $data['card_no']; // 卡号
        $card_exp = $data['card_exp']; // 信用卡有效期 1604
        $card_cvv2 = $data['card_cvv2']; // 信用卡校验码 488
        $card_name = $data['card_name']; // 持卡人姓名
        $card_idtype = $data['card_idtype']; // 持卡人证件类型 (I:身份证)
        $card_idno = $data['card_idno']; // 持卡人证件号码
        $card_phone = $data['card_phone']; // 持卡人电话号码
        $trade_type = $data['trade_type']; // 交易类型 (V：签约)
        $trade_id = $data['trade_id']; // 交易ID
        $trade_amount = $data['trade_amount']; // 金额
        $trade_currency = $data['trade_currency']; // 货币类型 CNY

        $data_xml = v_data_xml_create($card_bank, $card_type, $card_no, $card_exp, $card_cvv2, $card_name, $card_idtype, $card_idno, $card_phone, $trade_type, $trade_id, $trade_amount, $trade_currency);
        $service = new PayService();
        $resp = $service->trade($data_xml, $this->config);

        return $this->operatePrePurchase($resp);
    }

    protected function operatePrePurchase($resp)
    {
        $temResp = base64_decode(substr($resp, 5));
        $xml = simplexml_load_string($temResp);
        //验证签名, version.merchant.terminal.data
        $text = $xml->VERSION . $xml->MERCHANT . $xml->TERMINAL . $xml->DATA;

        if (!md5_verify($text, $this->config->md5, $xml->SIGN)) {
            return null;
        }

        $des = new DES($this->config->des);
        $decodedXML = $des->decrypt($xml->DATA);
        $dataXml = simplexml_load_string($decodedXML);
        /*
        <DATA>
            <TRADE>
              <TYPE>V</TYPE>
              <ID>12345670123</ID>
              <AMOUNT>1</AMOUNT>
              <CURRENCY>CNY</CURRENCY>
            </TRADE>
            <RETURN>
              <CODE>0000</CODE>
              <DESC>成功</DESC>
            </RETURN>
        </DATA>
        */

        return [
            'success' => ((string)$dataXml->RETURN->CODE) === '0000',
            'version' => $xml->VERSION,
            'merchant' => $xml->MERCHANT,
            'terminal' => $xml->TERMINAL,
            'trade_type' => $dataXml->TRADE->TYPE,
            'trade_id' => $dataXml->TRADE->ID,
            'trade_amount' => $dataXml->TRADE->AMOUNT,
            'trade_currency' => $dataXml->TRADE->CURRENCY,
            'return_code' => $dataXml->RETURN->CODE,
            'return_desc' => $dataXml->RETURN->DESC,
        ];
    }

    public function commitPurchase($data)
    {
        $card_bank = $data['card_bank']; // 银行编码
        $card_type = $data['card_type']; // 卡类型（信用卡：C借记卡：D）
        $card_no = $data['card_no']; // 卡号
        $card_exp = $data['card_exp']; // 信用卡有效期 1604
        $card_cvv2 = $data['card_cvv2']; // 信用卡校验码 488
        $card_name = $data['card_name']; // 持卡人姓名
        $card_idtype = $data['card_idtype']; // 持卡人证件类型 (I:身份证)
        $card_idno = $data['card_idno']; // 持卡人证件号码
        $card_phone = $data['card_phone']; // 持卡人电话号码
        $trade_type = $data['trade_type']; // 交易类型 (V：签约)
        $trade_id = $data['trade_id']; // 交易ID
        $trade_amount = $data['trade_amount']; // 金额
        $trade_currency = $data['trade_currency']; // 货币类型 CNY
        $trade_date = $data['trade_date']; // 日期 20140402
        $trade_time = $data['trade_time']; // 时间 183000
        $trade_notice = $data['trade_notice']; // 通知地址 如果填写，则异步发送结果通知到指定地址）
        $trade_note = $data['trade_note']; // 备注 "我要消费"
        $trade_code = $data['trade_code']; // 验证码

        $data_xml = s_data_xml_create($card_bank, $card_type, $card_no,
            $card_exp, $card_cvv2, $card_name,
            $card_idtype, $card_idno, $card_phone,
            $trade_type, $trade_id, $trade_amount,
            $trade_currency, $trade_date, $trade_time,
            $trade_notice, $trade_note, $trade_code);
        $service = new PayService();
        $resp = $service->trade($data_xml, $this->config);

        return $this->operateCommitPurchase($resp);
    }

    protected function operateCommitPurchase($resp)
    {
        $temResp = base64_decode(substr($resp, 5));
        $xml = simplexml_load_string($temResp);
        //验证签名, version.merchant.terminal.data
        $text = $xml->VERSION . $xml->MERCHANT . $xml->TERMINAL . $xml->DATA;
        if (!md5_verify($text, $this->config->md5, $xml->SIGN)) {
            return null;
        }
        //des密钥要网银在线后台设置
        $des = new DES($this->config->des);
        $decodedXML = $des->decrypt($xml->DATA);
        $dataXml = simplexml_load_string($decodedXML);

        /*
        <DATA>
            <TRADE>
              <TYPE>S</TYPE>
              <ID>12345670123</ID>
              <AMOUNT>1</AMOUNT>
              <CURRENCY>CNY</CURRENCY>
              <DATE/>
              <TIME/>
              <NOTE>我要消费</NOTE>
              <STATUS>6</STATUS>
            </TRADE>
            <RETURN>
              <CODE>EES0038</CODE>
              <DESC>原交易不允许此操作</DESC>
            </RETURN>
        </DATA>
         */
        // 成功：0 / 退款：3 / 部分退款：4 / 处理中：6 / 失败：7

        return [
            'success' => ((string)$dataXml->RETURN->CODE) === '0000' && ((string)$dataXml->TRADE->STATUS) === '0',
            'version' => $xml->VERSION,
            'merchant' => $xml->MERCHANT,
            'trade_type' => $dataXml->TRADE->TYPE,
            'trade_id' => $dataXml->TRADE->ID,
            'trade_amount' => $dataXml->TRADE->AMOUNT,
            'trade_currency' => $dataXml->TRADE->CURRENCY,
            'trade_date' => $dataXml->TRADE->DATE,
            'trade_time' => $dataXml->TRADE->TIME,
            'trade_note' => $dataXml->TRADE->NOTE,
            'trade_status' => $dataXml->TRADE->STATUS,
            'return_code' => $dataXml->RETURN->CODE,
            'return_desc' => $dataXml->RETURN->DESC,
        ];

    }
}
