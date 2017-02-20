<?php

namespace Being\Services\App;

use Being\Api\Service\Device\Device;
use Being\Api\Service\Device\DeviceClient;
use Being\Push\Message;
use Being\Push\Message\ApnsMessage;
use Being\Services\App\Jobs\Push;

class PushService
{
    /**
     * Usage:
     * 1. create a file config/push.php
     * <?php
     * return [
     *      'apns' => [
     *          'certificate_file' => '',
     *          'env' => 0, // 0-prod 1-sandbox
     *      ],
     *      baidu => [
     *          'api_key' => '',
     *          'api_secret' => '',
     *      ]
     * ];
     * 2. update bootstrap/app.php
     * add code
     * $app->configure('push');
     * after
     * $app->configure('app');
     * 3. php artisan queue:work --queue=queue --tries=1 --sleep=3 --daemon
     * @param $uidList
     * @param $title
     * @param null $pushConfig
     */
    public static function push($uidList, $title, $pushConfig = null)
    {
        if (is_null($pushConfig)) {
            $pushConfig = config('push');
        }

        $messages = null;
        $devices = app(DeviceClient::class)->pushTokens($uidList);
        if (count($devices) > 0) {
            foreach ($devices as $device) {
                $pushToken = $device->push_token;
                if ($device->device_type == Device::TYPE_IOS) {
                    $messages[] = (new ApnsMessage($pushToken, $title))
                        ->setCertificateFile($pushConfig['apns']['certificate_file'])
                        ->setEnv($pushConfig['apns']['env']);
                } elseif ($device->device_type == Device::TYPE_ANDROID) {
                    $pos = strpos($pushToken, ',');
                    if ($pos !== false) {
                        $pushToken = substr($pushToken, $pos + 1);
                    }
                    $messages[] = (new Message\BaiduMessage($pushToken, $title))
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