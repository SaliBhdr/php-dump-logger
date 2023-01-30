<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Factory;

use SaliBhdr\DumpLog\Dumpers\CliDumperStrategy;
use SaliBhdr\DumpLog\Dumpers\HtmlDumperStrategy;
use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Tests\TestCase;

class DumperFactoryTest extends TestCase
{
    public function testCanMakeCliDumper(): void
    {
        $dumper = Dumper::make('cli');

        $this->assertInstanceOf(CliDumperStrategy::class, $dumper);
    }

    public function testCanMakeHtmlDumper(): void
    {
        $dumper = Dumper::make('html');

        $this->assertInstanceOf(HtmlDumperStrategy::class, $dumper);
    }

    public function testCheckIfTheDefaultDumperIsCliDumper(): void
    {
        $dumper = Dumper::make();

        $this->assertInstanceOf(CliDumperStrategy::class, $dumper);
    }

    public function testCheckIfCliMethodWillReturnCliDumper(): void
    {
        $dumper = Dumper::cli();

        $this->assertInstanceOf(CliDumperStrategy::class, $dumper);
    }

    public function testCheckIfHtmlMethodWillReturnHtmlDumper(): void
    {
        $dumper = Dumper::html();

        $this->assertInstanceOf(HtmlDumperStrategy::class, $dumper);
    }
}
