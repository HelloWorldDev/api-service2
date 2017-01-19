<?php

namespace Being\Services\App;

// this class come from project "fame-manage"
// the document is on "https://docs.google.com/spreadsheets/d/1y6uE4iuTot6eK-CpIDN_0s6ehLXb-4ZPzwcJ4qifZEs/edit#gid=347979282"

class LanguagePackageService
{
    const IOS = 'ios';
    const ANDROID = 'android';
    const SERVER = 'server';

    /*
     * google-docs第一行:
     * 简体中文|zh-hans|values-zh-rCN|zh-cn	繁体中文|zh-hant|values-zh-rTW|zh-tw	英语|en|values|en
     * 泰语|th|values-th|th	日语|ja|values-ja|ja	俄语|ru|values-ru|ru
     * 备注说明说明	ANDROID	IOS	SERVER
     *
     * 说明:
     * "简体中文|zh-hans|values-zh-rCN|zh-cn",
     * 依次是中文说明,ios的标识,android的标识,server的标识.
     * 然后android,ios,server(不区分大小写)下面写该类型的key即可.
     * 注: 所有读取跟标识所在的列索引无关,只能标识的内容关联.
     */
    public static $availableKeys = [
        self::IOS,
        self::ANDROID,
        self::SERVER,
    ];

    public static function parseCSVFile($path)
    {
        $fp = fopen($path, 'r');

        $errors = $languageData = $deviceData = [];
        $availableKeys = self::$availableKeys;

        $firstLineInfo = fgetcsv($fp); // consume first line of title
        foreach ($firstLineInfo as $i => $info) {
            $infoLower = strtolower($info);
            if (in_array($infoLower, $availableKeys)) {
                $deviceData[$infoLower] = [
                    'device' => $infoLower,
                    'index' => $i,
                ];
            } elseif (strpos($info, '|') != false) {
                $langInfo = explode('|', $info);
                $langInfo = array_filter($langInfo);
                if (count($langInfo) == 4) {
                    for ($j = 1; $j < 4; ++$j) {
                        $languageData[] = [
                            'device' => $availableKeys[$j - 1],
                            'language' => $langInfo[$j],
                            'index' => $i,
                            'keyMap' => [],
                        ];
                    }
                }
            }
        }

        while (($data = fgetcsv($fp)) !== false) {
            foreach ($deviceData as $deviceVal) {
                $index = $deviceVal['index'];
                $device = $deviceVal['device'];
                if (!isset($data[$index])) {
                    continue;
                }
                if (!($key = trim($data[$index]))) {
                    continue;
                }
                foreach ($languageData as &$languageVal) {
                    if (isset($data[$languageVal['index']]) && $languageVal['device'] == $device) {
                        if (isset($languageVal['keyMap'][$key])) {
                            $errors[] = $key . ' already exists';
                        }
                        if ($data[$languageVal['index']] === '') {
                            $langName = $languageVal['language'] == 'values' || $languageVal['language'] == 'en' ? '英语' : $languageVal['language'];
                            $errors[] = $key . ' ' . $langName . ' not fill';
                        } else {
                            $languageVal['keyMap'][$key] = $data[$languageVal['index']];
                        }
                    }
                }
                unset($languageVal);
            }
        }

        @fclose($fp);

        return [$errors, $languageData];
    }
}
