<?php

namespace Being\Services\App;

use Being\Api\Service\Code;
use Being\Api\Service\User\User;
use Being\Api\Service\User\UserClient;
use Illuminate\Support\Facades\Redis;

class BindService
{
    protected static function getBindMobileLockCacheKey($mobile)
    {
        return 'being:bind:lock:mobile:' . $mobile;
    }

    protected static function getBindMobileVerifyCodeCacheKey($mobile)
    {
        return 'being:bind:verify:code:mobile:' . $mobile;
    }

    protected static function getBindMobileErrorTimesCacheKey($mobile)
    {
        return 'being:bind:error:times:mobile:' . $mobile;
    }

    /**
     * 发送绑定手机的短信
     * @param $mobile
     * @param $country
     * @param $message
     * @return int
     */
    public static function sendBindMobileMessage($mobile, $country, $message)
    {
        $formatMobile = MobileService::formatMobile($mobile, $country);
        if (Redis::get(self::getBindMobileLockCacheKey($formatMobile))) {
            return Code::SUCCESS;
        }

        $user = User::create(['mobile' => $mobile]);
        list($code, $data) = app(UserClient::class)->verify($user);
        if ($code != Code::SUCCESS) {
            return $code;
        }

        $ret = MobileService::sendSmsMessage($mobile, $country, $message);
        Redis::setex(self::getBindMobileLockCacheKey($formatMobile), 60, '1');

        if (!$ret) {
            return Code::SYSTEM_ERROR;
        }

        Redis::setex(self::getBindMobileVerifyCodeCacheKey($formatMobile), 600, $code);

        return Code::SUCCESS;
    }

    /**
     * 绑定手机号
     * @param $uid
     * @param $mobile
     * @param $country
     * @param $verifyCode
     * @param $callback
     * @return int
     */
    public static function bindMobile($uid, $mobile, $country, $verifyCode, $callback)
    {
        $formatMobile = MobileService::formatMobile($mobile, $country);
        $code = Redis::get(self::getBindMobileVerifyCodeCacheKey($formatMobile));

        if ($code != $verifyCode) {
            $errorTimes = Redis::incr(self::getBindMobileErrorTimesCacheKey($formatMobile));
            if ($errorTimes > 3 && !empty($code)) {
                Redis::del(self::getBindMobileVerifyCodeCacheKey($formatMobile));
            }

            return Code::VERIFY_CODE_NOT_MATCH;
        }

        $apiUser = User::create(['uid' => $uid, 'mobile' => $mobile]);
        list($code, $data) = app(UserClient::class)->updateUser($apiUser);
        if ($code != Code::SUCCESS) {
            return $code;
        }

        if (is_callable($callback) && !$callback()) {
            return Code::SYSTEM_ERROR;
        }

        return Code::SUCCESS;
    }

    protected static function getBindEmailLockCacheKey($email)
    {
        return 'being:bind:lock:email:' . $email;
    }

    protected static function getBindEmailVerifyCodeCacheKey($email)
    {
        return 'being:bind:verify:code:email:' . $email;
    }

    protected static function getBindEmailErrorTimesCacheKey($email)
    {
        return 'being:bind:error:times:email:' . $email;
    }

    /**
     * 发送绑定email的邮件
     * @param $email
     * @param $subject
     * @param $view
     * @param $data
     * @param $config
     * @return int
     */
    public static function sendBindEmailMessage($email, $subject, $view, $data, $config)
    {
        if (Redis::get(self::getBindEmailLockCacheKey($email))) {
            return Code::SUCCESS;
        }

        $user = User::create(['email' => $email]);
        list($code, $apiData) = app(UserClient::class)->verify($user);
        if ($code != Code::SUCCESS) {
            return $code;
        }

        $code = isset($data['code']) ? $data['code'] : rand(1000, 9999);
        $ret = EmailService::sendHtmlMail($email, $subject, $view, $data, $config);
        Redis::setex(self::getBindEmailLockCacheKey($email), 60, '1');

        if (!$ret) {
            return Code::SYSTEM_ERROR;
        }

        Redis::setex(self::getBindEmailVerifyCodeCacheKey($email), 600, $code);

        return Code::SUCCESS;
    }

    /**
     * 绑定邮箱
     * @param $uid
     * @param $email
     * @param $verifyCode
     * @param $callback
     * @return int
     */
    public static function bindEmail($uid, $email, $verifyCode, $callback)
    {
        $code = Redis::get(self::getBindEmailVerifyCodeCacheKey($email));

        if ($code != $verifyCode) {
            $errorTimes = Redis::incr(self::getBindEmailErrorTimesCacheKey($email));
            if ($errorTimes > 3 && !empty($code)) {
                Redis::del(self::getBindEmailVerifyCodeCacheKey($email));
            }

            return Code::VERIFY_CODE_NOT_MATCH;
        }

        $apiUser = User::create(['uid' => $uid, 'email' => $email]);
        list($code, $data) = app(UserClient::class)->updateUser($apiUser);
        if ($code != Code::SUCCESS) {
            return $code;
        }

        if (is_callable($callback) && !$callback()) {
            return Code::SYSTEM_ERROR;
        }

        return Code::SUCCESS;
    }
}