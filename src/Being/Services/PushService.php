<?php

namespace Being\Services;

use Being\Push\Message;
use Being\Push\Send;

class PushService
{
    /**
     * Push a Notification to Client By APNS, GCM, BaiduPush
     * @param Message $message
     * @return bool
     */
    public static function notification(Message $message)
    {
        return (new Send())->addMessage($message)->send();
    }
}
