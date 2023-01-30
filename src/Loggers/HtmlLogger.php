<?php

namespace SaliBhdr\DumpLog\Loggers;

use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Traits\LogsThroughRawLogger;

class HtmlLogger implements DumpLoggerInterface
{
    use LogsThroughRawLogger;

    /**
     * @var RawLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = (new RawLogger())
            ->dumper(Dumper::html())
            ->path($_SERVER['DOCUMENT_ROOT'] ?? null);
    }
}
