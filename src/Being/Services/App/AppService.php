<?php

namespace Being\Services\App;

class AppService
{
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
        $request = app('request');
        $appBundleId = $request->get('app_bundle_id');

        return !empty($appBundleId);
    }

    /**
     * Inspection request from AndroidAppClient
     * @return bool
     */
    public static function isAndroidAppClient()
    {
        $request = app('request');
        $packageName = $request->get('package_name');

        return !empty($packageName);
    }
}
