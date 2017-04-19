<?php

namespace Being\Services\App;

use Being\Api\Service\Push\PushClient;
use Being\Services\App\Jobs\Push;

class PushService
{
    public static function createMessage($uid, $title, $custom)
    {
        return [
            'uid' => intval($uid),
            'title' => $title,
            'custom' => $custom,
        ];
    }

    public static function push(array $messages, $async = false)
    {
        if ($async) {
            app('Illuminate\Contracts\Bus\Dispatcher')->dispatch((new Push($messages))->onQueue('being'));
        } else {
            app(PushClient::class)->push($messages);
        }
    }
}