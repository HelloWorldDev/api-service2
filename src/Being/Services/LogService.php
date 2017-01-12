<?php

namespace Being\Services;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

class LogService
{
    public static function pushDefaultMonoLogHandler(Logger $monoLog, $filename)
    {
        $monoLog->pushHandler(self::getDefaultMonoLogHandler($filename));
    }

    public static function getDefaultMonoLogHandler($filename)
    {
        return (new RotatingFileHandler($filename, 0, Logger::DEBUG))
            ->setFormatter(new LineFormatter(null, 'Y-m-d\TH:i:s.uP', true, true));
    }
}
