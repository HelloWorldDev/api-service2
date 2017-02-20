<?php

namespace Being\Services\App;

use Being\Api\Service\Device\Device;
use Being\Api\Service\Device\DeviceClient;
use Being\Push\Message;
use Being\Push\Message\ApnsMessage;
use Being\Services\App\Jobs\Push;

class PushService
{
    public static function push($uidList, $title)
    {
        $pushConfig = config('push');

        $messages = null;
        $devices = app(DeviceClient::class)->pushTokens($uidList);
        if (count($devices) > 0) {
            foreach ($devices as $device) {
                if ($device->device_type == Device::TYPE_IOS) {
                    $messages[] = (new ApnsMessage($device->push_token, $title))
                        ->setCertificateFile($pushConfig['apns']['certificate_file'])
                        ->setEnv($pushConfig['apns']['env']);
                } elseif ($device->device_type == Device::TYPE_ANDROID) {
                    $messages[] = (new Message\BaiduMessage($device->push_token, $title))
                        ->setApiKey($pushConfig['baidu']['api_key'])
                        ->setApiSecret($pushConfig['baidu']['api_secret']);
                }
            }
        }

        if (!is_null($messages)) {
            app('Illuminate\Contracts\Bus\Dispatcher')->dispatch(new Push($messages));
        }
    }
}