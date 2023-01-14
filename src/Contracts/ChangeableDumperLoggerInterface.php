<?php

namespace SaliBhdr\DumpLog\Contracts;

use Symfony\Component\VarDumper\Dumper\AbstractDumper;

interface ChangeableDumperLoggerInterface extends DumpLoggerInterface
{
    /**
     * @param AbstractDumper $dumper
     * @param string|null    $extension
     *
     * @return $this
     */
    public function dumper(AbstractDumper $dumper, string $extension): self;
}
