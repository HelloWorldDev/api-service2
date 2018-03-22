<?php

namespace Being\Services\App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

class LocalizationService
{
    protected static $langMap = [
        'zh-tw' => ['zh-hant', 'zh-tw', 'zh-hk', 'zh_hk', 'zh_tw'],
        'zh-cn' => ['zh-cn', 'zh-hans', 'hans', 'zh_cn', 'zh_CN'],
        'th' => ['th'],
        'en' => ['en'],
        'ja' => ['ja'],
        'ru' => ['ru'],
        'ko' => ['ko']
    ];

    public static function getLang()
    {
        $lang = Request::get('lang');
        if (empty($lang)) {
            $lang = 'zh-cn';
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
