<?php

namespace Being\Api\Service;

class Message
{
    /**
     * @param $code
     * @param $lang
     * @return string
     */
    public static function getMessage($code, $lang)
    {
        $path = __DIR__ . '/lang/' . $lang . '/message.php';
        if (file_exists($path)) {
            $langData = require __DIR__ . '/lang/' . $lang . '/message.php';
        } else {
            $langData = require __DIR__ . '/lang/en/message.php';
        }

        if (isset($langData[$code])) {
            return $langData[$code];
        } else {
            return null;
        }
    }
}
