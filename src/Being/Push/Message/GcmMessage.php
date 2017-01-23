<?php

namespace Being\Push\Message;

use Being\Push\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class GcmMessage extends Message
{
    protected $apiKey;

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function send()
    {
        if (is_null($this->apiKey)) {
            throw new \Exception('call GcmMessage::setApiKey to set ApiKey');
        }

        $method = 'POST';
        $uri = 'https://gcm-http.googleapis.com/gcm/send';
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . $this->apiKey,
        ];

        $message = ['id' => time(), 'content' => $this->title];
        $bodyArr = [
            'to' => $this->to,
            'data' => [
                'title' => $this->title,
                'message' => json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ]
        ];
        $body = json_encode($bodyArr);

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
}
