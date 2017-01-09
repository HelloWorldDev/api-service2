<?php

namespace Being\Services;

class ProfService
{
    protected static $transaction = 0;

    public static function begin()
    {
        if (self::$transaction == 0 && function_exists('xhprof_enable')) {
            self::$transaction++;
            xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        }
    }

    public static function end()
    {
        if (self::$transaction == 1 && function_exists('xhprof_disable')) {
            self::$transaction--;
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
