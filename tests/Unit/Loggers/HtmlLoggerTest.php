<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Loggers\HtmlLogger;
use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Loggers\RawLogger;

class HtmlLoggerTest extends TestCase
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

    public function testCanChangeTheLogFileNameInHtmlLog()
    {
        $data = 'test';

        $level = $this->strRandom();

        $this->logger->log($data, $level);

        $filePath = $this->getLogFilePath($level . '.html');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeThePathInHtmlLog()
    {
        $data = 'test';

        $path = $this->getLogsPath('tmp');

        $this->logger
            ->path($path)
            ->log($data);

        $filePath = $this->getLogFilePath('log.html', 'dump', 'tmp');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeTheDirectoryNameInHtmlLog()
    {
        $data = 'test';

        $this->logger
            ->dir('example')
            ->log($data);

        $filePath = $this->getLogFilePath('log.html', 'example');

        $this->assertFileExists($filePath);
    }

    public function testCanLogDailyInHtmlLog()
    {
        $data = 'test';

        $this->logger
            ->daily()
            ->log($data);

        $fileName = 'log-' . date('Y-m-d') . '.html';

        $filePath = $this->getLogFilePath($fileName);

        $this->assertFileExists($filePath);
    }

    public function testCanChangeLogDirPermissionOnCreationInHtmlLog()
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

    public function testCanCallLogSilentlyWithoutThrowingAnExceptionIfThePathNotDefined()
    {
        $result = $this->logger
            ->path('')
            ->silent()
            ->log('test');

        $filePath = $this->getLogFilePath('log.html');

        $this->assertFalse($result);
        $this->assertFileNotExists($filePath);
    }

    public function testCanLogSilentlyIfThePathIsDefined()
    {
        $result = $this->logger
            ->silent()
            ->log('test');

        $filePath = $this->getLogFilePath('log.html');

        $this->assertTrue($result);
        $this->assertFileExists($filePath);
    }

    public function testCanLogStringInHtmlLog()
    {
        $chars = 7;

        $data = $this->strRandom($chars);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '"<span class=sf-dump-str title="' . $chars . ' characters">' . $data . '</span>"');
    }

    public function testCanLogIntegerInHtmlLog()
    {
        $data = rand(111111111, 9999999999);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '<span class=sf-dump-num>' . $data . '</span>');
    }

    public function testCanLogDecimalInHtmlLog()
    {
        $data = 123.3666;

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.html'), '<span class=sf-dump-num>' . $data . '</span>');
    }

    public function testCanLogArrayInHtmlLog()
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

    public function testCanLogAssociativeArrayInHtmlLog()
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

    public function testCanLogMultiDimensionalArrayInHtmlLog()
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

        $log = '  "<span class=sf-dump-key>foo</span>" => <span class=sf-dump-note>array:3</span> [<samp>
    "<span class=sf-dump-key>foo</span>" => "<span class=sf-dump-str title="3 characters">bar</span>"
    "<span class=sf-dump-key>tar</span>" => "<span class=sf-dump-str title="2 characters">go</span>"
    "<span class=sf-dump-key>zee</span>" => "<span class=sf-dump-str title="5 characters">lorem</span>"
  </samp>]
  "<span class=sf-dump-key>tar</span>" => <span class=sf-dump-note>array:1</span> [<samp>
    "<span class=sf-dump-key>foo</span>" => "<span class=sf-dump-str title="3 characters">bar</span>"
  </samp>]
  "<span class=sf-dump-key>zee</span>" => "<span class=sf-dump-str title="5 characters">lorem</span>"';

        $this->assertFileContains($filePath, $log);
    }

    public function testCanLogStdObjectInHtmlLog()
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

    public function testCanLogClassesInHtmlLog()
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
}
