<?php

namespace Being\Services\App\Jobs;

use Being\Push\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Being\Api\Service\Push\PushClient;

class Push extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * @var array
     */
    protected $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function handle()
    {
        app(PushClient::class)->push($this->messages);
    }
}
