<?php

namespace Being\Services;

class ProfService
{
    public static function begin()
    {
        if (function_exists('xhprof_enable')) {
            xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        }
    }

    public static function end()
    {
        if (function_exists('xhprof_disable')) {
            $data = xhprof_disable();
            $file = (ini_get('xhprof.output_dir') ? : '/tmp') . '/' . uniqid() . '.xhprof.xhprof';
            file_put_contents($file, serialize($data));
        }
    }

    public static function prof(callable $callback, $catchException = true)
    {
        $ret = null;

        self::begin();
        if ($catchException) {
            try {
                $ret = $callback();
            } catch (\Exception $e) {
            }
        } else {
            $ret = $callback();
        }
        self::end();

        return $ret;
    }
}
