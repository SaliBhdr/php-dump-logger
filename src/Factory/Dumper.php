<?php

namespace SaliBhdr\DumpLog\Factory;

use SaliBhdr\DumpLog\Contracts\DumperStrategyInterface;
use SaliBhdr\DumpLog\Contracts\FactoryInterface;
use SaliBhdr\DumpLog\Dumpers\CliDumperStrategy;
use SaliBhdr\DumpLog\Dumpers\HtmlDumperStrategy;

class Dumper implements FactoryInterface
{
    public static function make(string $type = null): DumperStrategyInterface
    {
        switch ($type) {
            case 'html':
                return self::html();
            case 'cli':
            default:
                return self::cli();
        }
    }

    public static function html(): DumperStrategyInterface
    {
        return new HtmlDumperStrategy();
    }

    public static function cli(): DumperStrategyInterface
    {
        return new CliDumperStrategy();
    }
}
