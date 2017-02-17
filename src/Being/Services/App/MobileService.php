<?php

namespace Being\Services\App;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class MobileService
{
    /**
     * Extend Mobile Validator
     * @param $input
     */
    public static function extendMobileValidator($input)
    {
        Validator::extend('mobile', function ($attribute, $value, $parameters) use ($input) {
            if (!isset($input['code'], $input['country'], $input['mobile'])) {
                return false;
            }
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $swissNumberStr = $input['code'] . ' ' . $input['mobile'];
                $swissNumberProto = $phoneUtil->parse($swissNumberStr, strtoupper($input['country']));

                return $phoneUtil->isValidNumber($swissNumberProto);
            } catch (NumberParseException $e) {
                return false;
            }
        });
    }

    /**
     * Send Sms Message
     * @param $mobile
     * @param $message
     * @return bool
     */
    public static function sendSmsMessage($mobile, $message)
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

    /**
     * @param $mobile
     * @param $message
     * @param int $second
     * @return bool
     */
    public static function sendSmsMessageOnce($mobile, $message, $second = 60)
    {
        $cacheKey = 'being:send:sms:' . $mobile;
        if (Redis::get($cacheKey)) {
            return true;
        }
        // 2 second for api request time
        $second = max(1, $second - 2);
        Redis::setex($cacheKey, $second, '1');

        return self::sendSmsMessage($mobile, $message);
    }
}