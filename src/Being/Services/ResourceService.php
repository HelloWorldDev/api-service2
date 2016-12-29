<?php

namespace Being\Services;

class ResourceService
{
    /**
     * Convert a url to a key
     * This is for url of s3, qiniu, cloudfront.
     * @param $url
     * @return string
     */
    public static function url2key($url)
    {
        $questionMarkPos = null;
        $slashes = null;
        for ($i = 0, $len = strlen($url); $i < $len; ++$i) {
            if (is_null($questionMarkPos)) {
                if ($url[$i] == '/') {
                    $slashes[] = $i;
                } elseif ($url[$i] == '?') {
                    $questionMarkPos = $i;
                }
            }
        }

        if (strtolower(substr($url, 0, 7)) == 'http://') {
            if (isset($slashes[3])
                && (substr_count($url, 'cloudfront', 0, $slashes[2]) > 0
                    || substr_count($url, 'amazonaws', 0, $slashes[2]) > 0)
            ) {
                $pos = $slashes[3] + 1;
                $key = is_null($questionMarkPos) ? substr($url, $pos) : substr($url, $pos, $questionMarkPos - $pos);
            } elseif (isset($slashes[2])) {
                $pos = $slashes[2] + 1;
                $key = is_null($questionMarkPos) ? substr($url, $pos) : substr($url, $pos, $questionMarkPos - $pos);
            } else {
                $key = $url;
            }
        } else {
            $key = is_null($questionMarkPos) ? $url : substr($url, 0, $questionMarkPos);
        }

        return $key;
    }
}