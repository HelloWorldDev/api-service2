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
     * 发送绑定手机的邮件
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

        $code = rand(1000, 9999);
        $ret = MobileService::sendSmsMessage($mobile, $country, sprintf($message, $code));
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
}