<?php

namespace Being\Services\App\Jobs;

use Being\Services\App\AppService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class Http extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    protected $method;
    protected $url;
    protected $data;

    public function __construct($method, $url, $data)
    {
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
    }

    public function handle()
    {
        $client = new Client();
        try {
            $r = $client->request($this->method, $this->url, [
                'body' => http_build_query($this->data),
                'timeout' => 5,
            ]);

            AppService::debug(sprintf('request %s %s data %s success %d response %s',
                $this->method, $this->url, json_encode($this->data), $r->getStatusCode() == 200, $r->getBody()),
                __FILE__, __LINE__);

        } catch (Exception $e) {
            AppService::debug(sprintf('request %s %s data %s success %d error %s',
                $this->method, $this->url, json_encode($this->data), 0, $e->getMessage()),
                __FILE__, __LINE__);

            // try again
            throw new Exception($e->getMessage());
        }
    }
}
