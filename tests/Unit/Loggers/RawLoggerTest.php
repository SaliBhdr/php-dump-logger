<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use Mockery;
use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Exceptions\RuntimeException;
use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Loggers\RawLogger;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;

class RawLoggerTest extends TestCase
{
    public function testCanInstantiateLogger()
    {
        $logger = new RawLogger();

        $this->assertInstanceOf(RawLogger::class, $logger);
    }

    public function testCanLogDataWithDefaultDumper()
    {
        $data = 'test';

        (new RawLogger())
            ->dumper(Dumper::cli(), 'log')
            ->path($this->getLogsPath())
            ->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testItCanLogDataWithCliDumper()
    {
        $data = 'cli-test-log';

        (new RawLogger())
            ->dumper(Dumper::cli(), 'log')
            ->path($this->getLogsPath())
            ->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testItCanLogDataWithHtmlDumper()
    {
        $data = 'html-test-log';

        (new RawLogger())
            ->dumper(Dumper::html(), 'html')
            ->path($this->getLogsPath())
            ->log($data);

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testWillThrowInvalidArgumentExceptionIfPathNotDefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify log directory location with path() method, The $path to log directory should contain a value.');

        (new RawLogger())->dumper(Dumper::cli(), 'log')->log('test');
    }

    public function testItWillThrowRunTimeExceptionIfLogDirNotExits()
    {
        $this->expectException(RuntimeException::class);

        $fullPath = $this->getLogsPath();

        $logger = (new RawLogger())->dumper(Dumper::cli(), 'log')->path($fullPath);

        $this->callNotPublicMethod($logger, 'save', ['test', 'log', false]);
    }

    public function testItWillThrowInvalidArgumentExceptionIfDumperIsNotSpecifiedIfLogDirNotExits()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify a dumper and file extension with dumper() method.');

        (new RawLogger())->log('test');
    }

    public function testCanLogDataWithCustomDumper()
    {
        $data = 'test';

        $dumper = Mockery::mock(AbstractDumper::class);

        $dumper->expects('dump');

        $result = (new RawLogger())
            ->path($this->getLogsPath())
            ->dumper($dumper, 'log')
            ->log($data);

        $this->assertTrue($result);
    }

    public function testTheLogTimeIsCorrect()
    {
        (new RawLogger())
            ->path($this->getLogsPath())
            ->dumper(Dumper::cli(), 'log')
            ->log('test');

        $this->assertFileContains($this->getLogFilePath('log.log'), date('Y-m-d H:i:s'));
    }
}
