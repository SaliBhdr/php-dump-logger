<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Factory\Dumper;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Loggers\RawLogger;

class RawLoggerLevelTest extends TestCase
{
    /**
     * @var PrettyLogger
     */
    protected $logger;

    protected function setUp()
    {
        parent::setUp();

        $this->logger = (new RawLogger())
            ->path($this->getLogsPath())
            ->dumper(Dumper::cli(), 'log');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->logger = null;
    }

    public function testCanLogEmergencyLogLevelInRawLog()
    {
        $data = 'emergency';

        $this->logger->emergency($data);

        $filePath = $this->getLogFilePath('emergency.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogAlertLogLevelInRawLog()
    {
        $data = 'alert';

        $this->logger->alert($data);

        $filePath = $this->getLogFilePath('alert.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogCriticalLogLevelInRawLog()
    {
        $data = 'critical';

        $this->logger->critical($data);

        $filePath = $this->getLogFilePath('critical.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogErrorLogLevelInRawLog()
    {
        $data = 'error';

        $this->logger->error($data);

        $filePath = $this->getLogFilePath('error.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogWarningLogLevelInRawLog()
    {
        $data = 'warning';

        $this->logger->warning($data);

        $filePath = $this->getLogFilePath('warning.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogNoticeLogLevelInRawLog()
    {
        $data = 'notice';

        $this->logger->notice($data);

        $filePath = $this->getLogFilePath('notice.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogInfoLogLevelInRawLog()
    {
        $data = 'info';

        $this->logger->info($data);

        $filePath = $this->getLogFilePath('info.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogDebugLogLevelInRawLog()
    {
        $data = 'debug';

        $this->logger->debug($data);

        $filePath = $this->getLogFilePath('debug.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogExceptionWithoutTraceInRawLog()
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

    public function testCanLogExceptionWithTraceInRawLog()
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

    public function testCanLogDataWithCustomLevelInRawLog()
    {
        $data = 'test';

        $this->logger->log($data, 'custom-level');

        $filePath = $this->getLogFilePath('custom-level.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }
}
