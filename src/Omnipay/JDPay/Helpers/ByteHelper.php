<?php

namespace Omnipay\JDPay\Helpers;

class ByteHelper
{
    public static function getBytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i ++) {
            $bytes[] = ord($string[$i]);
        }

        return $bytes;
    }

    public static function hexStrToBytes($hexString)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($hexString) - 1; $i += 2) {
            $bytes[(int)($i / 2)] = hexdec($hexString[$i] . $hexString[$i + 1]) & 0xff;
        }

        return $bytes;
    }

    public static function ascToHex($asc, $AscLen)
    {
        $Hex = array();
        for ($i = 0; 2 * $i < $AscLen; $i ++) {
            $Hex[$i] = (chr($asc[2 * $i]) << 4);
            if (! (chr($asc[2 * $i]) >= '0' && chr($asc[2 * $i]) <= '9')) {
                $Hex[$i] += 0x90;
            }
            if (2 * $i + 1 >= $AscLen) {
                break;
            }
            $Hex[$i] |= (chr($asc[2 * $i + 1]) & 0x0f);
            if (! (chr($asc[2 * $i + 1]) >= '0' && chr($asc[2 * $i + 1]) <= '9')) {
                $Hex[$i] += 0x09;
            }
        }

        return $Hex;
    }

    public static function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i ++) {
            $tmp = dechex(ord($string[$i]));
            if (strlen($tmp) == 1) {
                $hex .= '0';
            }
            $hex .= $tmp;
        }
        $hex = strtolower($hex);

        return $hex;
    }

    public static function strToBytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i ++) {
            $bytes[] = ord($string[$i]);
        }

        return $bytes;
    }

    public static function toStr($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }

        return $str;
    }

    public static function bytesToHex($bytes)
    {
        return self::strToHex(self::toStr($bytes));
    }
    
    public static function integerToBytes($val)
    {
        $byt = array();
        $byt[0] = ($val >> 24 & 0xff);
        $byt[1] = ($val >> 16 & 0xff);
        $byt[2] = ($val >> 8 & 0xff);
        $byt[3] = ($val & 0xff);

        return $byt;
    }
    
    public static function bytesToInteger($bytes, $position)
    {
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;

        return $val;
    }

    public static function byteArrayToInt($b, $offset)
    {
        $value = 0;
        for ($i = 0; $i < 4; $i ++) {
            $shift = (4 - 1 - $i) * 8;
            $value = $value + ($b[$i + $offset] & 0x000000FF) << $shift; // 往高位游
        }

        return $value;
    }

    public static function shortToBytes($val)
    {
        $byt = array();
        $byt[0] = ($val & 0xff);
        $byt[1] = ($val >> 8 & 0xff);

        return $byt;
    }

    public static function bytesToShort($bytes, $position)
    {
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;

        return $val;
    }

    public static function hexToBin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin = '';
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack('H*', $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
                $sbin .= $c;
            }
            $i += 2;
        }

        return $sbin;
    }
}
