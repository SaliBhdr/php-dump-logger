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

    public function testCanLogEmergencyLogLevelInPrettyLog()
    {
        $data = 'emergency';

        $this->logger->emergency($data);

        $filePath = $this->getLogFilePath('emergency.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogAlertLogLevelInPrettyLog()
    {
        $data = 'alert';

        $this->logger->alert($data);

        $filePath = $this->getLogFilePath('alert.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogCriticalLogLevelInPrettyLog()
    {
        $data = 'critical';

        $this->logger->critical($data);

        $filePath = $this->getLogFilePath('critical.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogErrorLogLevelInPrettyLog()
    {
        $data = 'error';

        $this->logger->error($data);

        $filePath = $this->getLogFilePath('error.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogWarningLogLevelInPrettyLog()
    {
        $data = 'warning';

        $this->logger->warning($data);

        $filePath = $this->getLogFilePath('warning.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogNoticeLogLevelInPrettyLog()
    {
        $data = 'notice';

        $this->logger->notice($data);

        $filePath = $this->getLogFilePath('notice.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogInfoLogLevelInPrettyLog()
    {
        $data = 'info';

        $this->logger->info($data);

        $filePath = $this->getLogFilePath('info.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogDebugLogLevelInPrettyLog()
    {
        $data = 'debug';

        $this->logger->debug($data);

        $filePath = $this->getLogFilePath('debug.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanLogExceptionWithoutTraceInPrettyLog()
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

    public function testCanLogExceptionWithTraceInPrettyLog()
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

    public function testCanLogDataWithCustomLevelInPrettyLog()
    {
        $data = 'test';

        $this->logger->log($data, 'custom-level');

        $filePath = $this->getLogFilePath('custom-level.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }
}
