<?php

namespace Being\Services\App;

use Exception;
use GuzzleHttp\Client;


class SmsService
{
    public static function send($mobile, $message)
    {
        $smsApiKey = config('app.sms_api_key');
        $smsApiUrl = config('app.sms_api_url');

        $data = [
            'apikey' => $smsApiKey,
            'mobile' => $mobile,
            'text' => $message,
        ];

        $client = new Client();
        try {
            $r = $client->request('POST', $smsApiUrl, [
                'body' => http_build_query($data),
                'timeout' => 5,
            ]);
            $ret = $r->getStatusCode() == 200;

            AppService::debug(sprintf('send sms to %s with message %s failed ret %d', $mobile, $message, $ret),
                __FILE__, __LINE__);
            if ($ret) {
                return $r->getBody();
            }
        } catch (Exception $e) {
            AppService::error(sprintf('send sms to %s with message %s failed err %s', $mobile, $message, $e->getMessage()),
                __FILE__, __LINE__);
        }

        return false;
    }
}