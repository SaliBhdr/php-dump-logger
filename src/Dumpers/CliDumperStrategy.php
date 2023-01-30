<?php

namespace SaliBhdr\DumpLog\Dumpers;

use SaliBhdr\DumpLog\Contracts\DumperStrategyInterface;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class CliDumperStrategy implements DumperStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDumper(): AbstractDumper
    {
        return new CliDumper();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension(): string
    {
        return 'log';
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {
        $title = "\n\n";
        $title .= '---| ';
        $title .= date('Y-m-d H:i:s');
        $title .= ' |-------------------------------------------------------------------------------------------';
        $title .= "\n\n";

        return $title;
    }
}
