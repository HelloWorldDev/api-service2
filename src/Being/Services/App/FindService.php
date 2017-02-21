<?php

namespace Being\Services\App;

use Being\Api\Service\Code;
use Illuminate\Support\Facades\Redis;

class FindService
{
    protected static function getFindByMobileLockCacheKey($mobile)
    {
        return 'being:find:lock:mobile:' . $mobile;
    }

    protected static function getFindByMobileVerifyCodeCacheKey($mobile)
    {
        return 'being:find:verify:code:mobile:' . $mobile;
    }

    protected static function getFindByMobileErrorTimesCacheKey($mobile)
    {
        return 'being:find:error:times:mobile:' . $mobile;
    }

    /**
     * 发送找回密码的短信
     * @param $mobile
     * @param $country
     * @param $message
     * @param $verifyCode
     * @return int
     */
    public static function sendFindPasswordMobileMessage($mobile, $country, $message, $verifyCode)
    {
        $formatMobile = MobileService::formatMobile($mobile, $country);
        if (Redis::get(self::getFindByMobileLockCacheKey($formatMobile))) {
            return Code::SUCCESS;
        }

        $ret = MobileService::sendSmsMessage($mobile, $country, $message);
        Redis::setex(self::getFindByMobileLockCacheKey($formatMobile), 60, '1');

        if (!$ret) {
            return Code::SYSTEM_ERROR;
        }

        Redis::setex(self::getFindByMobileVerifyCodeCacheKey($formatMobile), 600, $verifyCode);

        return Code::SUCCESS;
    }

    /**
     * @param $mobile
     * @param $verifyCode
     * @return bool
     */
    public static function checkFindPasswordVerifyCodeByMobile($mobile, $verifyCode)
    {
        if (empty($verifyCode)) {
            return false;
        }

        $code = Redis::get(self::getFindByMobileVerifyCodeCacheKey($mobile));

        return $code == $verifyCode;
    }

    protected static function getFindByEmailLockCacheKey($email)
    {
        return 'being:find:lock:email:' . $email;
    }

    protected static function getFindByEmailVerifyCodeCacheKey($email)
    {
        return 'being:find:verify:code:email:' . $email;
    }

    protected static function getFindByEmailErrorTimesCacheKey($email)
    {
        return 'being:find:error:times:email:' . $email;
    }

    /**
     * 发送找回密码的邮件
     * @param $email
     * @param $subject
     * @param $view
     * @param $data
     * @param $config
     * @return int
     */
    public static function sendFindPasswordEmailMessage($email, $subject, $view, $data, $config)
    {
        if (Redis::get(self::getFindByEmailLockCacheKey($email))) {
            return Code::SUCCESS;
        }

        $verifyCode = isset($data['code']) ? $data['code'] : rand(1000, 9999);
        $ret = EmailService::sendHtmlMail($email, $subject, $view, $data, $config);
        Redis::setex(self::getFindByEmailLockCacheKey($email), 60, '1');

        if (!$ret) {
            return Code::SYSTEM_ERROR;
        }

        Redis::setex(self::getFindByEmailVerifyCodeCacheKey($email), 600, $verifyCode);

        return Code::SUCCESS;
    }

    /**
     * @param $email
     * @param $verifyCode
     * @return bool
     */
    public static function checkFindPasswordVerifyCodeByEmail($email, $verifyCode)
    {
        if (empty($verifyCode)) {
            return false;
        }

        $code = Redis::get(self::getFindByEmailVerifyCodeCacheKey($email));

        return $code == $verifyCode;
    }
}