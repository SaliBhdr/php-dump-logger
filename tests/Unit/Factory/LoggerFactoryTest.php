<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Factory;

use SaliBhdr\DumpLog\Factory\Logger;
use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Tests\TestCase;

class LoggerFactoryTest extends TestCase
{
    public function testCanMakePrettyLogger()
    {
        $dumper = Logger::make('pretty');

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }

    public function testCanMakeHtmlLogger()
    {
        $dumper = Logger::make('html');

        $this->assertInstanceOf(HtmlLogger::class, $dumper);
    }

    public function testTheDefaultLoggerIsPrettyLogger()
    {
        $dumper = Logger::make();

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }

    public function testPrettyMethodWillReturnPrettyLogger()
    {
        $dumper = Logger::html();

        $this->assertInstanceOf(HtmlLogger::class, $dumper);
    }

    public function testHtmlMethodWillReturnHtmlLogger()
    {
        $dumper = Logger::pretty();

        $this->assertInstanceOf(PrettyLogger::class, $dumper);
    }
}
