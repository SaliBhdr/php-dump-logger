<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Loggers\PrettyLogger;
use SaliBhdr\DumpLog\Loggers\RawLogger;

class PrettyLoggerTest extends TestCase
{
    /**
     * @var PrettyLogger
     */
    protected $logger;

    protected function setUp()
    {
        parent::setUp();

        $this->logger = (new PrettyLogger)
            ->path($this->getLogsPath());
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->logger = null;
    }

    public function testCanChangeTheLogFileNameInPrettyLog()
    {
        $data = 'test';

        $level = $this->strRandom();

        $this->logger->log($data, $level);

        $filePath = $this->getLogFilePath($level . '.log');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeThePathInPrettyLog()
    {
        $data = 'test';

        $path = $this->getLogsPath('tmp');

        $this->logger
            ->path($path)
            ->log($data);

        $filePath = $this->getLogFilePath('log.log', 'dump', 'tmp');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeTheDirectoryNameInPrettyLog()
    {
        $data = 'test';

        $this->logger
            ->dir('example')
            ->log($data);

        $filePath = $this->getLogFilePath('log.log', 'example');

        $this->assertFileExists($filePath);
    }

    public function testCanLogDailyInPrettyLog()
    {
        $data = 'test';

        $this->logger
            ->daily()
            ->log($data);

        $fileName = 'log-' . date('Y-m-d') . '.log';

        $filePath = $this->getLogFilePath($fileName);

        $this->assertFileExists($filePath);
    }

    public function testCanChangeLogDirPermissionOnCreationInPrettyLog()
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

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFalse($result);
        $this->assertFileNotExists($filePath);
    }

    public function testCanLogSilentlyIfThePathIsDefined()
    {
        $result = $this->logger
            ->silent()
            ->log('test');

        $filePath = $this->getLogFilePath('log.log');

        $this->assertTrue($result);
        $this->assertFileExists($filePath);
    }

    public function testCanLogStringInPrettyLog()
    {
        $data = $this->strRandom(7);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.log'), $data);
    }

    public function testCanLogIntegerInPrettyLog()
    {
        $data = rand(111111111, 9999999999);

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.log'), $data);
    }

    public function testCanLogDecimalInPrettyLog()
    {
        $data = 123.3666;

        $this->logger->log($data);

        $this->assertFileContains($this->getLogFilePath('log.log'), $data);
    }

    public function testCanLogArrayInPrettyLog()
    {
        $data = ['1', '2', '3', '4'];

        $this->logger->log($data);

        $log = 'array:4 [
  0 => "1"
  1 => "2"
  2 => "3"
  3 => "4"
]';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }

    public function testCanLogAssociativeArrayInPrettyLog()
    {
        $data = [
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        $this->logger->log($data);

        $log = 'array:3 [
  "foo" => "bar"
  "tar" => "go"
  "zee" => "lorem"
]';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }

    public function testCanLogMultiDimensionalArrayInPrettyLog()
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

        $log = 'array:3 [
  "foo" => array:3 [
    "foo" => "bar"
    "tar" => "go"
    "zee" => "lorem"
  ]
  "tar" => array:1 [
    "foo" => "bar"
  ]
  "zee" => "lorem"
]';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }

    public function testCanLogStdObjectInPrettyLog()
    {
        $data = (object) [
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileContains($filePath, '+"foo": "bar"');
        $this->assertFileContains($filePath, '+"tar": "go"');
        $this->assertFileContains($filePath, '+"zee": "lorem"');
    }

    public function testCanLogClassesInPrettyLog()
    {
        $data = new RawLogger;

        $this->logger->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileContains($filePath, 'SaliBhdr\DumpLog\Loggers\RawLogger');
        $this->assertFileContains($filePath, '#isDaily: false');
        $this->assertFileContains($filePath, '#path: null');
        $this->assertFileContains($filePath, '#dir: "dump"');
        $this->assertFileContains($filePath, '#permission: 509');
        $this->assertFileContains($filePath, '#dumper: null');
        $this->assertFileContains($filePath, '#extension: null');
        $this->assertFileContains($filePath, '#silent: false');
    }
}
