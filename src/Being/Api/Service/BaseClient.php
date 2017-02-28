<?php

namespace Being\Api\Service;

class BaseClient
{
    protected $httpClient;
    public $appID;
    public $appSecret;

    public function __construct(Sender $httpClient, $appID, $appSecret)
    {
        $this->httpClient = $httpClient;
        $this->appID = $appID;
        $this->appSecret = $appSecret;
    }

    public function parseResponseBody($body)
    {
        $resp = json_decode($body, true);
        if (isset($resp['code'])) {
            if ($resp['code'] == Code::SUCCESS) {
                return [$resp['code'], $resp['data']];
            } elseif (isset($resp['message'])) {
                return [$resp['code'], $resp['message']];
            }
        }

        return [Code::EMPTY_BODY, null];
    }

    protected function getSecretData()
    {
        return [
            'app_id' => $this->appID,
            'app_secret' => $this->appSecret,
        ];
    }

    protected function getSecretHeader()
    {
        return [
            'App-ID' => $this->appID,
            'App-Secret' => $this->appSecret,
        ];
    }
}
