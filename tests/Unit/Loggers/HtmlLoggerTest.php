<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\RawLogger;

class HtmlLoggerTest extends TestCase
{
    /**
     * @var HtmlLogger
     */
    protected $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = (new HtmlLogger)
            ->path($this->getLogsPath());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->logger = null;
    }

    public function testCanChangeTheLogFileNameInHtmlLog(): void
    {
        $data = 'test';

        $level = $this->strRandom();

        $this->logger->log($data, $level);

        $filePath = $this->getLogFilePath($level . '.html');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeThePathInHtmlLog(): void
    {
        $data = 'test';

        $path = $this->getLogsPath('tmp');

        $this->logger
            ->path($path)
            ->log($data);

        $filePath = $this->getLogFilePath('log.html', 'dump', 'tmp');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeTheDirectoryNameInHtmlLog(): void
    {
        $data = 'test';

        $this->logger
            ->dir('example')
            ->log($data);

        $filePath = $this->getLogFilePath('log.html', 'example');

        $this->assertFileExists($filePath);
    }

    public function testCanLogDailyInHtmlLog(): void
    {
        $data = 'test';

        $this->logger
            ->daily()
            ->log($data);

        $fileName = 'log-' . date('Y-m-d') . '.html';

        $filePath = $this->getLogFilePath($fileName);

        $this->assertFileExists($filePath);
    }

    public function testCanChangeLogDirPermissionOnCreationInHtmlLog(): void
    {
        $this->logger
            ->permission(0777)
            ->log('test');

        $this->assertFilePermission($this->getLogsDir(), '0777');

        $this->removeDir($this->getLogsPath());

        $this->logger
            ->permission(0770)
            ->path($this->getLogsPath())
            ->log('test');

        $this->assertFilePermission($this->getLogsDir(), '0770');
    }

    public function testCanCallLogSilentlyWithoutThrowingAnExceptionIfThePathNotDefined(): void
    {
        $result = $this->logger
            ->path('')
            ->silent()
            ->log('test');

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFalse($result);
        $this->assertFileNotExists($filePath);
    }

    public function testCanLogSilentlyIfThePathIsDefined(): void
    {
        $result = $this->logger
            ->silent()
            ->log('test');

        $filePath = $this->getLogFilePath('log.html');

        $this->assertTrue($result);
        $this->assertFileExists($filePath);
    }

    public function testCanLogStringInHtmlLog(): void
    {
        $chars = 7;

        $data = $this->strRandom($chars);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '"<span class=sf-dump-str title="' . $chars . ' characters">' . $data . '</span>"');
    }

    public function testCanLogIntegerInHtmlLog(): void
    {
        $data = rand(111111111, 9999999999);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '<span class=sf-dump-num>' . $data . '</span>');
    }

    public function testCanLogDecimalInHtmlLog(): void
    {
        $data = 123.3666;

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '<span class=sf-dump-num>' . $data . '</span>');
    }

    public function testCanLogArrayInHtmlLog(): void
    {
        $data = ['1', '2', '3', '4'];

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFileContains($filePath, '<span class=sf-dump-note>array:4</span>');

        $log = '<span class=sf-dump-index>0</span> => "<span class=sf-dump-str>1</span>"
  <span class=sf-dump-index>1</span> => "<span class=sf-dump-str>2</span>"
  <span class=sf-dump-index>2</span> => "<span class=sf-dump-str>3</span>"
  <span class=sf-dump-index>3</span> => "<span class=sf-dump-str>4</span>"';

        $this->assertFileContains($filePath, $log);
    }

    public function testCanLogAssociativeArrayInHtmlLog(): void
    {
        $data = [
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFileContains($filePath, '<span class=sf-dump-note>array:3</span>');

        $log = '  "<span class=sf-dump-key>foo</span>" => "<span class=sf-dump-str title="3 characters">bar</span>"
  "<span class=sf-dump-key>tar</span>" => "<span class=sf-dump-str title="2 characters">go</span>"
  "<span class=sf-dump-key>zee</span>" => "<span class=sf-dump-str title="5 characters">lorem</span>"';

        $this->assertFileContains($filePath, $log);
    }

    public function testCanLogMultiDimensionalArrayInHtmlLog(): void
    {
        $data = [
            'foo' => [
                'foo' => 'bar',
                'tar' => 'go',
                'zee' => 'lorem',
            ],
            'tar' => [
                'foo' => 'bar',
            ],
            'zee' => 'lorem',
        ];

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFileContains($filePath, '<span class=sf-dump-note>array:3</span>');

        $log = '  "<span class=sf-dump-key>foo</span>" => <span class=sf-dump-note>array:3</span> [<samp data-depth=2 class=sf-dump-compact>
    "<span class=sf-dump-key>foo</span>" => "<span class=sf-dump-str title="3 characters">bar</span>"
    "<span class=sf-dump-key>tar</span>" => "<span class=sf-dump-str title="2 characters">go</span>"
    "<span class=sf-dump-key>zee</span>" => "<span class=sf-dump-str title="5 characters">lorem</span>"
  </samp>]
  "<span class=sf-dump-key>tar</span>" => <span class=sf-dump-note>array:1</span> [<samp data-depth=2 class=sf-dump-compact>
    "<span class=sf-dump-key>foo</span>" => "<span class=sf-dump-str title="3 characters">bar</span>"
  </samp>]
  "<span class=sf-dump-key>zee</span>" => "<span class=sf-dump-str title="5 characters">lorem</span>"';

        $this->assertFileContains($filePath, $log);
    }

    public function testCanLogStdObjectInHtmlLog(): void
    {
        $data = (object) [
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        $this->logger->log($data);

        $log = '  +"<span class=sf-dump-public title="Runtime added dynamic property">foo</span>": "<span class=sf-dump-str title="3 characters">bar</span>"
  +"<span class=sf-dump-public title="Runtime added dynamic property">tar</span>": "<span class=sf-dump-str title="2 characters">go</span>"
  +"<span class=sf-dump-public title="Runtime added dynamic property">zee</span>": "<span class=sf-dump-str title="5 characters">lorem</span>"';

        $this->assertFileContains($this->getLogFilePath('log.html'), $log);
    }

    public function testCanLogClassesInHtmlLog(): void
    {
        $data = new RawLogger();

        $this->logger->log($data);

        $log = '#<span class=sf-dump-protected title="Protected property">isDaily</span>: <span class=sf-dump-const>false</span>
  #<span class=sf-dump-protected title="Protected property">path</span>: <span class=sf-dump-const>null</span>
  #<span class=sf-dump-protected title="Protected property">dir</span>: "<span class=sf-dump-str title="4 characters">dump</span>"
  #<span class=sf-dump-protected title="Protected property">permission</span>: <span class=sf-dump-num>509</span>
  #<span class=sf-dump-protected title="Protected property">dumper</span>: <span class=sf-dump-const>null</span>
  #<span class=sf-dump-protected title="Protected property">extension</span>: <span class=sf-dump-const>null</span>
  #<span class=sf-dump-protected title="Protected property">silent</span>: <span class=sf-dump-const>false</span>';

        $this->assertFileContains($this->getLogFilePath('log.html'), $log);
    }

    public function testCanLogEmergencyLogLevelInHtmlLog(): void
    {
        $data = 'emergency';

        $this->logger->emergency($data);

        $filePath = $this->getLogFilePath('emergency.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="9 characters">' . $data . '</span>"');
    }

    public function testCanLogAlertLogLevelInHtmlLog(): void
    {
        $data = 'alert';

        $this->logger->alert($data);

        $filePath = $this->getLogFilePath('alert.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogCriticalLogLevelInHtmlLog(): void
    {
        $data = 'critical';

        $this->logger->critical($data);

        $filePath = $this->getLogFilePath('critical.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="8 characters">' . $data . '</span>"');
    }

    public function testCanLogErrorLogLevelInHtmlLog(): void
    {
        $data = 'error';

        $this->logger->error($data);

        $filePath = $this->getLogFilePath('error.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogWarningLogLevelInHtmlLog(): void
    {
        $data = 'warning';

        $this->logger->warning($data);

        $filePath = $this->getLogFilePath('warning.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="7 characters">' . $data . '</span>"');
    }

    public function testCanLogNoticeLogLevelInHtmlLog(): void
    {
        $data = 'notice';

        $this->logger->notice($data);

        $filePath = $this->getLogFilePath('notice.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="6 characters">' . $data . '</span>"');
    }

    public function testCanLogInfoLogLevelInHtmlLog(): void
    {
        $data = 'info';

        $this->logger->info($data);

        $filePath = $this->getLogFilePath('info.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="4 characters">' . $data . '</span>"');
    }

    public function testCanLogDebugLogLevelInHtmlLog(): void
    {
        $data = 'debug';

        $this->logger->debug($data);

        $filePath = $this->getLogFilePath('debug.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="5 characters">' . $data . '</span>"');
    }

    public function testCanLogExceptionWithoutTraceInHtmlLog(): void
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

    public function testCanLogExceptionWithTraceInHtmlLog(): void
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

    public function testCanLogDataWithCustomLevelInHtmlLog(): void
    {
        $data = 'test';

        $this->logger->log($data, 'custom-level');

        $filePath = $this->getLogFilePath('custom-level.html');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, '"<span class=sf-dump-str title="4 characters">' . $data . '</span>"');
    }
}
