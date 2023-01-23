<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Factory;

use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Tests\TestCase;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class DumperFactoryTest extends TestCase
{
    public function testCanMakeCliDumper(): void
    {
        $dumper = Dumper::make('cli');

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }

    public function testCanMakeHtmlDumper(): void
    {
        $dumper = Dumper::make('html');

        $this->assertInstanceOf(HtmlDumper::class, $dumper);
    }

    public function testTheDefaultDumperIsCliDumper(): void
    {
        $dumper = Dumper::make();

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }

    public function testCliMethodWillReturnCliDumper(): void
    {
        $dumper = Dumper::cli();

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }

    public function testHtmlMethodWillReturnHtmlDumper(): void
    {
        $dumper = Dumper::html();

        $this->assertInstanceOf(HtmlDumper::class, $dumper);
    }
}
