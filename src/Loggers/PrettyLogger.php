<?php

namespace SaliBhdr\DumpLog\Loggers;

use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Traits\LogsThroughRawLogger;

class PrettyLogger implements DumpLoggerInterface
{
    use LogsThroughRawLogger;

    /**
     * @var RawLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = (new RawLogger())
            ->dumper(Dumper::cli(), 'log')
            ->path($_SERVER['DOCUMENT_ROOT'] ?? null);
    }
}
