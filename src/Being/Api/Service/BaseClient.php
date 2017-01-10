<?php

namespace Being\Api\Service;

use Being\Api\Auth;
use Being\Api\Request;
use Being\Api\Response;

class BaseClient
{
    protected $auth;
    protected $logFile;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;

        return $this;
    }

    protected function returnInt(Response $response)
    {
        if ($this->isResponseValid($response)) {
            return intval($response->getData()['error_code']);
        } else {
            return Code::INVALID_RESPONSE;
        }
    }

    protected function isResponseValid(Response $response)
    {
        return $response->isSuccess() && isset($response->getData()['error_code']);
    }

    /**
     * @param $method
     * @param $endpoint
     * @return Request
     */
    protected function buildRequest($method, $endpoint)
    {
        return (new Request($this->auth, $method, $endpoint))
            ->setLogFile($this->logFile);
    }

}