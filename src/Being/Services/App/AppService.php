<?php

namespace Being\Services\App;

use Being\Api\Service\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redis;

class AppService
{
    /**
     * Log debug message
     * Usage: AppService::debug('debug message', __FILE__, __LINE__);
     * @param array|string $data
     * @param string $file
     * @param int $line
     */
    public static function debug($data, $file, $line)
    {
        Log::debug(self::getLogContent($data, $file, $line));
    }

    /**
     * Log error message
     * Usage: AppService::error('error message', __FILE__, __LINE__);
     * @param array|string $data
     * @param string $file
     * @param int $line
     */
    public static function error($data, $file, $line)
    {
        Log::error(self::getLogContent($data, $file, $line));
    }

    /**
     * Get log content
     * @param $data
     * @param $file
     * @param $line
     * @return string
     */
    protected static function getLogContent($data, $file, $line)
    {
        return sprintf('file:%s:%d request_id:%s message:%s', $file, $line,
            isset($_SERVER['X_REQUEST_ID']) ? $_SERVER['X_REQUEST_ID'] : '',
            is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }


    /**
     * Response Success Data
     * @param $data
     * @param null $common
     * @return mixed
     */
    public static function response($data = ['result' => 'ok'], $common = null)
    {
        return self::responseCore(['data' => $data, 'common' => $common]);
    }

    /**
     * Response Error Data
     * @param $code
     * @param string $message
     * @return mixed
     */
    public static function responseError($code, $message = null)
    {
        if (is_null($message)) {
            $message = Message::getMessage($code, LocalizationService::getLang());
        }

        return self::responseCore(['error_code' => $code, 'message' => $message]);
    }

    /**
     * Response Client
     * @param $response
     * @param int $status
     * @return mixed
     */
    public static function responseCore($response, $status = 200)
    {
        $request = app('request');
        $requestMethod = $request->method();
        $requestPath = $request->path();
        $responseBody = json_encode($response);

        $sign = $request->get('sign', $request->header('sign'));
        $timestamp = $request->get('timestamp', $request->header('timestamp'));
        $requestUid = property_exists($request, 'uid') ? $request->uid : 0;
        $queries = $request->all();
        unset($queries['password'], $queries['old_password']);
        $queries['sign'] = $sign;
        $queries['timestamp'] = $timestamp;
        $queries['request_id'] = isset($_SERVER['X_REQUEST_ID']) ? $_SERVER['X_REQUEST_ID'] : '';
        $queries['request_uid'] = $requestUid;
        $queries['request_method'] = $requestMethod;
        $queries['request_path'] = $requestPath;
        $queryParamStr = json_encode($queries, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $log = sprintf('Request:%s Response:%s', $queryParamStr, $responseBody);
        Log::debug($log);

        $enableETag = intval($request->get('etag', 1));
        if ($requestMethod == 'GET'
            && env('OPEN_ETAG', true)
            && $enableETag == 1
            && !isset($response['error_code'])
        ) {
            $eTag = $request->header('If-None-Match');
            if (self::checkETag($eTag, $responseBody)) {
                $headers['Etag'] = sprintf('"%s"', $eTag);

                return response('', 304, $headers);
            }
        }

        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $headers['Cache-Control'] = 'public';

        return response()->json($response, $status, $headers);
    }

    /**
     * Check whether eTag is changed
     * @param $eTag
     * @param $responseBody
     * @return bool
     */
    protected static function checkETag($eTag, $responseBody)
    {
        $left = strpos($eTag, '"');
        $right = strrpos($eTag, '"');
        $eTag = (substr($eTag, $left + 1, $right - $left - 1));
        $newETag = md5($responseBody);

        return $eTag == $newETag;
    }

    /**
     * Format the limit param for preventing large data request destroy
     * This is for laravel framework
     * @param $request
     * @param int $default
     * @param int $max
     * @param string $key
     * @return int
     */
    public static function limit($request, $default = 10, $max = 100, $key = 'limit')
    {
        $limit = $request->input($key);
        if (is_null($limit)) {
            return $default;
        }
        $max = max($default, $max);
        $ret = $limit > $max ? $max : ($limit < 0 ? 0 : intval($limit));

        return $ret;
    }

    /**
     * Inspection request from iOSAppClient
     * @return bool
     */
    public static function isiOSAppClient()
    {
        $appBundleId = Request::get('app_bundle_id');

        return !empty($appBundleId);
    }

    /**
     * Inspection request from AndroidAppClient
     * @return bool
     */
    public static function isAndroidAppClient()
    {
        $packageName = Request::get('package_name');

        return !empty($packageName);
    }

    /**
     * Get Laravel or Lumen MonoLog Instance
     * @return null
     */
    public static function getMonoLog()
    {
        if (function_exists('app')) {
            return app('log');
        }

        return null;
    }

    /**
     * Get country code from common params
     * @return string
     */
    public static function getCountryCode()
    {
        $countryCode = Request::get('network_country_iso');
        if (empty($countryCode)) {
            $countryCode = Request::get('country', '');
        }

        return strtoupper($countryCode);
    }

    /**
     * Get app_bundle_id from common params
     * @return string
     */
    public static function getAppBundleId()
    {
        $appBundleId = Request::get('app_bundle_id');
        if (empty($appBundleId)) {
            $appBundleId = Request::get('package_name', '');
        }

        return $appBundleId;
    }

    /**
     * Compare app version
     * if ( AppService::compareAppVersion('1.0.0') == 0 ) {
     * }
     * @param $version
     * @return int -1: 小于$version; 0: 等于version; 1: 大于$version
     */
    public static function compareAppVersion($version)
    {
        $appVersion = Request::get('app_version');

        return version_compare($appVersion, $version);
    }

    /**
     * 更新用户token
     * @param integer $uid 用户id
     * @param bool $new  是否更新token
     * @param string $prefix 前缀
     * @return array
     */
    public static function getSignData($uid, $new = false, $prefix = '')
    {
        $tokensKey = sprintf('sign:data:%s:tokens', $prefix);
        $usersKey = sprintf('sign:data:%s:users', $prefix);

        $oldToken = null;
        if (!$new) {
            if ($oldToken = Redis::hget($usersKey, $uid)) {
                if ($tokenEncodeData = Redis::hget($tokensKey, $oldToken)) {
                    if ($tokenData = json_decode($tokenEncodeData, true)) {
                        return ['token' => $oldToken, 'secret' => $tokenData['secret']];
                    }
                }
            }
        }

        if ($oldToken || ($oldToken = Redis::hget($usersKey, $uid))) {
            Redis::hdel($tokensKey, $oldToken);
        }

        $token = md5($uid . microtime() . rand(1000, 9999) . '@nb');
        $secret = md5($token . time());
        $tokenData = ['uid' => $uid, 'secret' => $secret];
        Redis::hset($usersKey, $uid, $token);
        Redis::hset($tokensKey, $token, json_encode($tokenData));

        return ['token' => $token, 'secret' => $secret];
    }
}
