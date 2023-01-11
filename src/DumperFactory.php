<?php

namespace SaliBhdr\DumpLog;

use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class DumperFactory
{
    public static function make(string $type = null)
    {
        switch ($type) {
            case 'html':
                return new HtmlDumper();
            case 'cli':
            default:
                return new CliDumper();
        }
    }
}
