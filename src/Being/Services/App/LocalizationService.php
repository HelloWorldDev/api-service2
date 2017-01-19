<?php

namespace App\Services\App;

use Illuminate\Support\Str;

class LocalizationService
{

    protected static $langMap = [
        'zh-tw' => ['zh-hant', 'zh-tw', 'zh-hk', 'zh_hk', 'zh_tw'],
        'zh-cn' => ['zh-cn', 'zh-hans', 'hans', 'zh_cn'],
        'th' => ['th'],
        'en' => ['en'],
        'ja' => ['ja'],
        'ru' => ['ru'],
    ];

    public static function getLang()
    {
        $lang = app('request')->get('lang');
        if (empty($lang)) {
            $lang = 'en';
        }

        $lang = strtolower($lang);
        foreach (self::$langMap as $key => $value) {
            foreach ($value as $prefix) {
                if (Str::startsWith($lang, $prefix)) {
                    $lang = $key;
                    goto out;
                }
            }
        }

        out:

        return $lang;
    }
}
