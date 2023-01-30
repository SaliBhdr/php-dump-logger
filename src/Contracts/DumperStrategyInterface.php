<?php

namespace SaliBhdr\DumpLog\Contracts;

use Symfony\Component\VarDumper\Dumper\AbstractDumper;

interface DumperStrategyInterface
{
    /**
     * The dumper that is used for rendering the log content
     *
     * @return AbstractDumper
     */
    public function getDumper(): AbstractDumper;

    /**
     * The extension of log file
     *
     * @return string
     */
    public function getExtension(): string;

    /**
     * Title will be shown on top of each log (usually it's better to add the date and time)
     *
     * @return string
     */
    public function getTitle(): string;
}
