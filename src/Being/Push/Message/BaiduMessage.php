<?php

namespace Being\Push\Message;

use Being\Push\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class BaiduMessage extends Message
{
    protected $apiKey;
    protected $apiSecret;

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function send()
    {
        if (is_null($this->apiKey)) {
            throw new \Exception('call BaiduMessage::setApiKey to set ApiKey');
        }
        if (is_null($this->apiSecret)) {
            throw new \Exception('call BaiduMessage::setApiSecret to set ApiSecret');
        }

        $method = 'POST';
        $uri = 'http://api.tuisong.baidu.com/rest/3.0/push/single_device';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
            'User-Agent' => $this->makeUA(),
        ];

        $data = [
            // 'description' => $this->title,
            'id' => time(),
            'content' => $this->title,
            'msg_type' => 0,
            'uri' => ''
        ];
        if (is_array($this->options)) {
            if (isset($this->options['custom'])) {
                $data = array_merge($data, $this->options['custom']);
            }
        }
        if ($this->description) {
            $data['description'] = $this->description;
        }

        $params = [
            'apikey' => $this->apiKey,
            'timestamp' => time(),
            'device_type' => 3, // 3 android; 4 ios
            'channel_id' => $this->to,
            'msg' => json_encode($data),
            'msg_type' => 0, // 0 消息; 1 通知
        ];
        $params['sign'] = $this->genSign($this->apiSecret, $method, $uri, $params);
        $body = http_build_query($params);

        $client = new Client();
        $request = new Request($method, $uri, $headers, $body);

        $response = $client->send($request);
        $ret = $response->getStatusCode() == 200;

        // log
        if (class_exists('\Being\Services\App\AppService')) {
            $message = json_encode([
                'request_uri' => $request->getUri()->__toString(),
                'request_method' => $request->getMethod(),
                'request_header' => $request->getHeaders(),
                'request_body' => $request->getBody()->__toString(),
                'response_body' => $response->getBody()->__toString(),
                'response_code' => $response->getStatusCode(),
                'response_reason_phrase' => $response->getReasonPhrase(),
            ]);
            if ($ret) {
                \Being\Services\App\AppService::debug($message, __FILE__, __LINE__);
            } else {
                \Being\Services\App\AppService::error($message, __FILE__, __LINE__);
            }
        }

        return $ret;
    }

    protected function genSign($secret_key, $method, $url, $arrContent)
    {
        $gather = $method . $url;
        ksort($arrContent);
        foreach ($arrContent as $key => $value) {
            $gather .= $key . '=' . $value;
        }
        $gather .= $secret_key;
        $sign = md5(urlencode($gather));

        return $sign;
    }

    protected function makeUA()
    {
        $sdkVersion = '3.0.0';

        $sysName = php_uname('s');
        $sysVersion = php_uname('v');
        $machineName = php_uname('m');

        $systemInfo = "$sysName; $sysVersion; $machineName";

        $langName = 'PHP';
        $langVersion = phpversion();

        $serverName = php_sapi_name();
        $serverVersion = "Unknown";

        $sendInfo = 'ZEND/' . zend_version();

        $serverInfo = "$serverName/$serverVersion";

        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $serverInfo .= '(' . $_SERVER['SERVER_SOFTWARE'] . ')';
        }
        $tpl = "BCCS_SDK/3.0 ($systemInfo) $langName/$langVersion (Baidu Push SDK for PHP v$sdkVersion) $serverInfo $sendInfo";

        return $tpl;
    }
}
