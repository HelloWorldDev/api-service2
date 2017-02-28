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
        $langData = require __DIR__ . '/lang/' . $lang . '/message.php';
        if (isset($langData[$code])) {
            return $langData[$code];
        } else {
            return null;
        }
    }
}
