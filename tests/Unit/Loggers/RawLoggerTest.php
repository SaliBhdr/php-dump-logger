<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use Mockery;
use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Exceptions\RuntimeException;
use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Loggers\RawLogger;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;

class RawLoggerTest extends TestCase
{
    /**
     * @var PrettyLogger
     */
    protected $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = (new RawLogger())
            ->dumper(Dumper::cli())
            ->path($this->getLogsPath());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->logger = null;
    }

    public function testCheckIfItCanLogDataWithCliDumperInRawLog(): void
    {
        $data = 'cli-test-log';

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCheckIfItCanLogDataWithHtmlDumperInRawLog(): void
    {
        $data = 'html-test-log';

        $this->logger
            ->dumper(Dumper::html())
            ->log($data);

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCheckIfItWillThrowInvalidArgumentExceptionIfPathNotDefinedInRawLog(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify log directory location with path() method, The $path to log directory should contain a value.');

        (new RawLogger())
            ->dumper(Dumper::cli())
            ->log('test');
    }

    public function testCheckIfItWillNotThrowInvalidArgumentExceptionIfPathNotDefinedAndTheLoggerCalledSilentlyInRawLog(): void
    {
        $result = (new RawLogger())
            ->dumper(Dumper::cli())
            ->silent()
            ->log('test');

        $this->assertFalse($result);
    }

    public function testCheckIfItWillThrowRunTimeExceptionIfLogDirNotExitsInRawLog(): void
    {
        $this->expectException(RuntimeException::class);

        $fullPath = $this->getLogsPath();

        $logger = (new RawLogger())
            ->dumper(Dumper::cli())
            ->path($fullPath);

        $this->callNotPublicMethod($logger, 'save', ['test', 'log', false]);
    }

    public function testCheckIfItWillThrowInvalidArgumentExceptionIfDumperIsNotSpecifiedAndLogDirNotExitsInRawLog(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify a dumper strategy with dumper() method.');

        (new RawLogger())->log('test');
    }

    public function testCheckIfItWillNotThrowInvalidArgumentExceptionIfDumperIsNotSpecifiedAndLogDirNotExitsAndTheLoggerCalledSilentlyInRawLog(): void
    {
        $result = (new RawLogger())
            ->silent()
            ->log('test');

        $this->assertFalse($result);
    }

    public function testCanLogDataWithCustomDumperInRawLog(): void
    {
        $data = 'test';

        $dumper = Mockery::mock(AbstractDumper::class);

        $dumper->expects('dump');

        $result = $this->logger->log($data);

        $this->assertTrue($result);
    }

    public function testCheckIfTheLogTimeIsCorrectInRawLog(): void
    {
        $this->logger->log('test');

        $this->assertFileContains($this->getLogFilePath('log.log'), date('Y-m-d H:i:s'));
    }

    public function testCanLogEmergencyLogLevelInRawLog(): void
    {
        $data = 'emergency';

        $this->logger->emergency($data);

        $filePath = $this->getLogFilePath('emergency.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogAlertLogLevelInRawLog(): void
    {
        $data = 'alert';

        $this->logger->alert($data);

        $filePath = $this->getLogFilePath('alert.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogCriticalLogLevelInRawLog(): void
    {
        $data = 'critical';

        $this->logger->critical($data);

        $filePath = $this->getLogFilePath('critical.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogErrorLogLevelInRawLog(): void
    {
        $data = 'error';

        $this->logger->error($data);

        $filePath = $this->getLogFilePath('error.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogWarningLogLevelInRawLog(): void
    {
        $data = 'warning';

        $this->logger->warning($data);

        $filePath = $this->getLogFilePath('warning.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogNoticeLogLevelInRawLog(): void
    {
        $data = 'notice';

        $this->logger->notice($data);

        $filePath = $this->getLogFilePath('notice.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogInfoLogLevelInRawLog(): void
    {
        $data = 'info';

        $this->logger->info($data);

        $filePath = $this->getLogFilePath('info.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogDebugLogLevelInRawLog(): void
    {
        $data = 'debug';

        $this->logger->debug($data);

        $filePath = $this->getLogFilePath('debug.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogExceptionWithoutTraceInRawLog(): void
    {
        try {
            throw new \Exception(
                'exception message',
                '500'
            );
        } catch (\Throwable $e) {
            $line = $e->getLine();
            $this->logger->exception($e);
        }

        $filePath = $this->getLogFilePath('exception.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, 'array:5');
        $this->assertFileContains($filePath, '"class" => "Exception"');
        $this->assertFileContains($filePath, '"massage" => "exception message"');
        $this->assertFileContains($filePath, '"code" => 500');
        $this->assertFileContains($filePath, '"file"');
        $this->assertFileContains($filePath, '"line" => ' . ($line ?? 0));
    }

    public function testCanLogExceptionWithTraceInRawLog(): void
    {
        try {
            throw new \Exception(
                'exception message',
                '500'
            );
        } catch (\Throwable $e) {
            $line = $e->getLine();
            $this->logger->exception($e, true);
        }

        $filePath = $this->getLogFilePath('exception.log');

        $this->assertFileExists($filePath);
        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, 'array:6');
        $this->assertFileContains($filePath, '"class" => "Exception"');
        $this->assertFileContains($filePath, '"massage" => "exception message"');
        $this->assertFileContains($filePath, '"code" => 500');
        $this->assertFileContains($filePath, '"file"');
        $this->assertFileContains($filePath, '"line" => ' . ($line ?? 0));
        $this->assertFileContains($filePath, '"trace"');
    }

    public function testCanLogDataWithCustomLevelInRawLog(): void
    {
        $data = 'test';

        $this->logger->log($data, 'custom-level');

        $filePath = $this->getLogFilePath('custom-level.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogDataDailyWithCustomLevelInRawLog(): void
    {
        $data = 'test';

        $this->logger
            ->daily()
            ->log($data, 'custom-level');

        $fileName = 'custom-level-' . date('Y-m-d') . '.log';

        $filePath = $this->getLogFilePath($fileName);

        $this->assertFileExists($filePath);
    }
}
