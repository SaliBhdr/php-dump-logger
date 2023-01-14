<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Factory;

use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Tests\TestCase;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class DumperFactoryTest extends TestCase
{
    public function testCanMakeCliDumper()
    {
        $dumper = Dumper::make('cli');

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }

    public function testCanMakeHtmlDumper()
    {
        $dumper = Dumper::make('html');

        $this->assertInstanceOf(HtmlDumper::class, $dumper);
    }

    public function testTheDefaultDumperIsCliDumper()
    {
        $dumper = Dumper::make();

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }

    public function testCliMethodWillReturnCliDumper()
    {
        $dumper = Dumper::html();

        $this->assertInstanceOf(HtmlDumper::class, $dumper);
    }

    public function testHtmlMethodWillReturnHtmlDumper()
    {
        $dumper = Dumper::cli();

        $this->assertInstanceOf(CliDumper::class, $dumper);
    }
}
