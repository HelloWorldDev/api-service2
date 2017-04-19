<?php

namespace Being\Services\App;

use Being\Services\App\Jobs\Http;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
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
     * Format Phone Number
     * @param $mobile
     * @param $countryCode
     * @return string
     */
    public static function formatMobile($mobile, $countryCode)
    {
        if (strlen($countryCode) > 0) {
            $countryCode = strtoupper($countryCode);
            try {
                $phoneUtil = PhoneNumberUtil::getInstance();
                $numberPrototype = $phoneUtil->parse($mobile, $countryCode);
                $formatNum = $phoneUtil->format($numberPrototype, PhoneNumberFormat::E164);

                return $formatNum;
            } catch (\Exception $e) {
                AppService::debug(sprintf('format phone number %s country code %s failed', $mobile, $countryCode),
                    __FILE__, __LINE__);
            }
        }

        return $mobile;
    }

    /**
     * Parse Mobile
     * @param $mobile
     * @param $countryCode
     * @return array ['mobile' => $mobile, 'country' => $countryCode, 'code' => '']
     */
    public static function parseMobile($mobile, $countryCode)
    {
        $countryCode = strtoupper($countryCode);
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $phoneNumber = $phoneUtil->parse($mobile, $countryCode);

            return [
                'mobile' => $phoneNumber->getNationalNumber(),
                'country' => $countryCode,
                'code' => $phoneNumber->getCountryCode(),
            ];
        } catch (\Exception $e) {
            AppService::debug(sprintf('format phone number %s country code %s failed', $mobile, $countryCode),
                __FILE__, __LINE__);
        }

        return ['mobile' => $mobile, 'country' => $countryCode, 'code' => ''];
    }

    /**
     * @param $mobile
     * @param $countryCode
     * @param $message
     * @return bool
     */
    public static function sendSmsMessage($mobile, $countryCode, $message)
    {
        $smsApiKey = config('app.sms_api_key');
        $smsApiUrl = config('app.sms_api_url');

        $mobile = self::formatMobile($mobile, $countryCode);

        $data = [
            'apikey' => $smsApiKey,
            'mobile' => $mobile,
            'text' => $message,
        ];

        $job = new Http('POST', $smsApiUrl, $data);
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);

        return true;
    }

    /**
     * @param $mobile
     * @param $message
     * @param $countryCode
     * @param $second
     * @return bool
     */
    public static function sendSmsMessageOnce($mobile, $countryCode, $message, $second)
    {
        $mobile = self::formatMobile($mobile, $countryCode);
        $cacheKey = 'being:send:sms:' . $mobile;
        if (Redis::get($cacheKey)) {
            return true;
        }
        // 2 second for api request time
        $second = max(1, $second - 2);
        Redis::setex($cacheKey, $second, '1');

        return self::sendSmsMessage($mobile, $countryCode, $message);
    }

    /**
     * @param $mobile
     * @return string
     */
    public static function hiddenMobile($mobile)
    {
        if (empty($mobile)) {
            return '';
        }
        return substr($mobile, 0, 3) . '****' . substr($mobile, -4);
    }
}
