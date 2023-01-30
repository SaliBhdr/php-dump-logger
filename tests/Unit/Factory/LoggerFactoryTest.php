<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Factory;

use SaliBhdr\DumpLog\Factory\Logger;
use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Tests\TestCase;

class LoggerFactoryTest extends TestCase
{
    public function testCanMakePrettyLogger(): void
    {
        $dumper = Logger::make('pretty');

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }

    public function testCanMakeHtmlLogger(): void
    {
        $dumper = Logger::make('html');

        $this->assertInstanceOf(HtmlLogger::class, $dumper);
    }

    public function testCheckIfTheDefaultLoggerIsPrettyLogger(): void
    {
        $dumper = Logger::make();

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }

    public function testCheckIfPrettyMethodWillReturnPrettyLogger(): void
    {
        $dumper = Logger::html();

        $this->assertInstanceOf(HtmlLogger::class, $dumper);
    }

    public function testCheckIfHtmlMethodWillReturnHtmlLogger(): void
    {
        $dumper = Logger::pretty();

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }
}
