<?php

namespace SaliBhdr\DumpLog\Factory;

use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Contracts\FactoryInterface;
use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;

class Logger implements FactoryInterface
{
    public static function make(string $type = null): DumpLoggerInterface
    {
        switch ($type) {
            case 'html':
                return self::html();
            case 'pretty':
            default:
                return self::pretty();
        }
    }
    public static function html(): DumpLoggerInterface
    {
        return new HtmlLogger();
    }
    public static function pretty(): DumpLoggerInterface
    {
        return new PrettyLogger();
    }
}
