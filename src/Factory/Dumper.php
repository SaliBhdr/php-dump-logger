<?php

namespace SaliBhdr\DumpLog\Factory;

use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class Dumper
{
    public static function make(string $type = null): AbstractDumper
    {
        switch ($type) {
            case 'html':
                return self::html();
            case 'cli':
            default:
                return self::cli();
        }
    }

    public static function html(): AbstractDumper
    {
        return new HtmlDumper();
    }

    public static function cli(): AbstractDumper
    {
        return new CliDumper();
    }
}
