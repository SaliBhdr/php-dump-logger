<?php

namespace SaliBhdr\DumpLog\Contracts;

interface DumpLoggerAwareInterface extends DumpLoggerInterface
{
    /**
     * This method helps to change the symfony's dumper
     *
     * @param DumperStrategyInterface $dumper
     *
     * @return $this
     */
    public function dumper(DumperStrategyInterface $dumper): self;
}
