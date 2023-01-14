<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;

class HtmlLoggerLevelTest extends TestCase
{
    /**
     * @var PrettyLogger
     */
    protected $logger;

    protected function setUp()
    {
        parent::setUp();

        $this->logger = (new HtmlLogger)
            ->path($this->getLogsPath());
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->logger = null;
    }

    public function testCanLogEmergencyLogLevelInHtmlLog()
    {
        $data = 'emergency';

        $this->logger->emergency($data);

        $filePath = $this->getLogFilePath('emergency.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="9 characters">' . $data . '</span>"');
    }

    public function testCanLogAlertLogLevelInHtmlLog()
    {
        $data = 'alert';

        $this->logger->alert($data);

        $filePath = $this->getLogFilePath('alert.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogCriticalLogLevelInHtmlLog()
    {
        $data = 'critical';

        $this->logger->critical($data);

        $filePath = $this->getLogFilePath('critical.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="8 characters">' . $data . '</span>"');
    }

    public function testCanLogErrorLogLevelInHtmlLog()
    {
        $data = 'error';

        $this->logger->error($data);

        $filePath = $this->getLogFilePath('error.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogWarningLogLevelInHtmlLog()
    {
        $data = 'warning';

        $this->logger->warning($data);

        $filePath = $this->getLogFilePath('warning.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="7 characters">' . $data . '</span>"');
    }

    public function testCanLogNoticeLogLevelInHtmlLog()
    {
        $data = 'notice';

        $this->logger->notice($data);

        $filePath = $this->getLogFilePath('notice.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="6 characters">' . $data . '</span>"');
    }

    public function testCanLogInfoLogLevelInHtmlLog()
    {
        $data = 'info';

        $this->logger->info($data);

        $filePath = $this->getLogFilePath('info.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="4 characters">' . $data . '</span>"');
    }

    public function testCanLogDebugLogLevelInHtmlLog()
    {
        $data = 'debug';

        $this->logger->debug($data);

        $filePath = $this->getLogFilePath('debug.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogExceptionWithoutTraceInHtmlLog()
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

        $filePath = $this->getLogFilePath('exception.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '<span class=sf-dump-note>array:5</span>');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>class</span>" => "<span class=sf-dump-str title="9 characters">Exception</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>massage</span>" => "<span class=sf-dump-str title="17 characters">exception message</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>code</span>" => <span class=sf-dump-num>500</span>');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>file</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>line</span>" => <span class=sf-dump-num>' . $line ?? 0 . '</span>');
    }

    public function testCanLogExceptionWithTraceInHtmlLog()
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

        $filePath = $this->getLogFilePath('exception.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '<span class=sf-dump-note>array:6</span>');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>class</span>" => "<span class=sf-dump-str title="9 characters">Exception</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>massage</span>" => "<span class=sf-dump-str title="17 characters">exception message</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>code</span>" => <span class=sf-dump-num>500</span>');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>file</span>"');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>line</span>" => <span class=sf-dump-num>' . $line ?? 0 . '</span>');
        $this->assertFileContains($filePath, '"<span class=sf-dump-key>trace</span>"');
    }

    public function testCanLogDataWithCustomLevelInHtmlLog()
    {
        $data = 'test';

        $this->logger->log($data, 'custom-level');

        $filePath = $this->getLogFilePath('custom-level.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="4 characters">' . $data . '</span>"');
    }
}
