<?php

namespace Being\Api;

use Being\Services\App\AppService;
use Being\Services\LogService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface;
use Monolog\Logger;

class Request
{
    protected $endpoint;
    protected $method;
    protected $auth;
    protected $timeout;
    protected $async;
    protected $query;
    protected $param;
    protected $body;
    protected $log;
    protected $logFile;

    public function __construct(Auth $auth, $method, $endpoint)
    {
        $this->auth = $auth;
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->async = false;
        $this->timeout = 3;
        /*
         * laravel & lumen support
         */
        $this->log = AppService::getMonoLog();
    }

    /**
     * @return \Being\Api\Response
     */
    public function send()
    {
        if ($this->async) {
            return $this->sendRequest();
        } else {
            return $this->sendAsyncRequest();
        }
    }

    public function setAsync($async)
    {
        $this->async = $async;

        return $this;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    public function setParam(array $posts)
    {
        $this->param = $posts;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setLog(Logger $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @param $logFile
     * @return Request
     */
    public function setLogFile($logFile)
    {
        if (!empty($logFile)) {
            $this->logFile = $logFile;
            if (is_null($this->log)) {
                $this->log = new Logger('production');
            }

            LogService::pushDefaultMonoLogHandler($this->log, $logFile);
        }

        return $this;
    }

    protected function sendRequest()
    {
        $client = new Client();
        $request = new Psr7Request(
            $this->method,
            $this->getUri(),
            $this->getHeaders(),
            $this->getBody()
        );
        $options = ['timeout' => $this->timeout];

        $response = $client->send($request, $options);
        $level = $response->getStatusCode() == 200 ? Logger::DEBUG : Logger::ERROR;
        $this->log($level, $request, $response);

        return $this->parseResponse($response);
    }

    protected function sendAsyncRequest()
    {
        $client = new Client();
        $request = new Psr7Request(
            $this->method,
            $this->getUri(),
            $this->getHeaders(),
            $this->getBody()
        );
        $options = ['timeout' => $this->timeout];
        $promise = $client->sendAsync($request, $options)->then(function ($response) use ($request) {
            $this->log(Logger::DEBUG, $request, $response);
        }, function (\Exception $e) use ($request) {
            $response = new Psr7Response($e->getCode(), [], null, '1.1', $e->getMessage());
            $this->log(Logger::ERROR, $request, $response);
        });

        $promise->wait();

        return new Response();
    }

    protected function getUri()
    {
        if (is_null($this->query)) {
            return $this->endpoint;
        } else {
            return sprintf('%s?%s', $this->endpoint, http_build_query($this->query));
        }
    }

    protected function getHeaders()
    {
        return [];
    }

    protected function getBody()
    {
        if (!is_null($this->body)) {
            return $this->body;
        } elseif (!is_null($this->param)) {
            return http_build_query($this->param);
        } else {
            return null;
        }
    }

    protected function parseResponseBody($body)
    {
        return json_decode($body, true);
    }

    protected function parseResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return (new Response())
                ->setAsSuccess()
                ->setData(
                    $this->parseResponseBody($response->getBody())
                );
        } else {
            return (new Response())
                ->setCode($statusCode)
                ->setMessage($response->getReasonPhrase());
        }
    }

    protected function log($level, Psr7Request $request, ResponseInterface $response)
    {
        if (!is_null($this->log)) {
            $message = json_encode([
                'request_uri' => $this->endpoint,
                'request_method' => $this->method,
                'request_body' => $this->body,
                'request_query' => $this->query,
                'request_param' => $this->param,
                'response_body' => $response->getBody()->__toString(),
                'response_code' => $response->getStatusCode(),
                'response_reason_phrase' => $response->getReasonPhrase(),
            ]);
            $this->log->log($level, $message);
        }
    }
}