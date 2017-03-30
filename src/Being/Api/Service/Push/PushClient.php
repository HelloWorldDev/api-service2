<?php

namespace Being\Api\Service\Push;

use Being\Api\Service\BaseClient;
use Being\Api\Service\Code;
use Being\Api\Service\HttpClient;

class PushClient extends BaseClient implements PushInterface
{
    /**
     * @param array $messages
     * 单个message的结构
     * {
     *       uid: int, // 必填
     *       title: string, // 必填
     *       badge: int,
     *       custom: object, // 可以是任何自定义结构
     * }
     * @return bool
     */
    public function push(array $messages)
    {
        $header = $this->getSecretHeader();
        $req = HttpClient::getRequest(HttpClient::POST, 'v1/send', [], $header, json_encode($messages));
        list($code, $body, $header) = $this->httpClient->send($req);
        list($code) = $this->parseResponseBody($body);

        return $code == Code::SUCCESS;
    }
}
