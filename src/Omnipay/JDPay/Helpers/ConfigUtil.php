<?php

namespace Omnipay\JDPay\Helpers;

class ConfigUtil
{
    protected static $configFile;

    public static function setConfigFile($configFile)
    {
        self::$configFile = $configFile;
    }

    public static function get_val_by_key($key)
    {
        $settings = new Settings_INI ();
        $settings->load(self::$configFile);
        return $settings->get("wepay." . $key);
    }

    public static function get_trade_num()
    {
        return ConfigUtil::get_val_by_key('merchantNum') . ConfigUtil::getMillisecond();
    }

    public static function getMillisecond()
    {
        list ($s1, $s2) = explode(' ', microtime());
        return ( float )sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}

abstract class Settings
{
    var $_settings = array();

    function get($var)
    {
        $var = explode('.', $var);

        $result = $this->_settings;
        foreach ($var as $key) {
            if (!isset ($result [$key])) {
                return false;
            }

            $result = $result [$key];
        }

        return $result;
    }

    abstract function load($file);
}

class Settings_INI extends Settings
{
    function load($file)
    {
        if (file_exists($file) == true) {
            $this->_settings = parse_ini_file($file, true);
        }
    }
}