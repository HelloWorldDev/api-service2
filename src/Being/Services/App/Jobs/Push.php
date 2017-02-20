<?php

namespace Being\Services\App\Jobs;

use Being\Push\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class Push extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * @var Message[]
     */
    protected $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function handle()
    {
        foreach ($this->messages as $message) {
            $message->send();
        }
    }
}
