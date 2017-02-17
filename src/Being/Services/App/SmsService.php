<?php

namespace Being\Services\App;

use Exception;
use GuzzleHttp\Client;

class SmsService
{
    /**
     * Send Sms Message
     * @param $mobile
     * @param $message
     * @return bool
     */
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

            AppService::debug(sprintf('send sms to %s with message %s failed ret %d body %s',
                $mobile, $message, $r->getStatusCode() == 200, $r->getBody()),
                __FILE__, __LINE__);

            return $r->getStatusCode() == 200;
        } catch (Exception $e) {
            AppService::error(sprintf('send sms to %s with message %s failed err %s', $mobile, $message, $e->getMessage()),
                __FILE__, __LINE__);
        }

        return false;
    }
}