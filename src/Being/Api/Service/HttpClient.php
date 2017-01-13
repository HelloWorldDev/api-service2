<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 3:28 PM
 */

namespace Being\Api\Service;

use Being\Services\App\AppService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements Sender
{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    public $client;
    protected $logger;

    public function __construct($baseURL)
    {
        $this->client = new Client(['base_uri' => $baseURL]);
        $this->logger = AppService::getMonoLog();
    }

    public static function getRequest($method, $uri, $queries, $headers, $body)
    {
        if (is_array($queries) && count($queries) > 0) {
            $uri = substr($uri, 0, strpos($uri, '?'));
            $uri .= sprintf("?%s", http_build_query($queries));
        }

        if (is_array($body)) {
            $body = http_build_query($body);
        }

        $req = new Request($method, $uri, $headers, $body);

        return $req;
    }

    public function send(Request $request)
    {
        try {
            $resp = $this->client->send($request);

            $code = $resp->getStatusCode();
            $body = $resp->getBody();
            $headers = $resp->getHeaders();

            return [$code, $body, $headers];
        } catch (\Exception $e) {
            $this->log(Logger::ERROR, $e->getMessage());
            return [$e->getCode(), $e->getMessage(), null];
        }
    }

    protected function logResponse($level, Request $request, ResponseInterface $response)
    {
        $message = json_encode([
            'request_uri' => $request->getUri(),
            'request_method' => $request->getMethod(),
            'request_header' => $request->getHeaders(),
            'request_body' => $request->getBody(),
            'response_body' => $response->getBody()->__toString(),
            'response_code' => $response->getStatusCode(),
            'response_reason_phrase' => $response->getReasonPhrase(),
        ]);
        $this->logger->log($level, $message);
    }

    protected function log($level, $msg)
    {
        if (!is_null($this->logger)) {
            $this->logger->log($level, $msg);
        }
    }
}
