<?php

namespace SaliBhdr\DumpLog\Tests;

use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Logger;

class NormalLoggerTest extends LoggerTestCase
{
    public function testCanInstantiateLoggerWithInitMethod()
    {
        $logger = Logger::init();

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testLoggerThrowsInvalidArgumentExceptionIfPathNotDefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify log directory location with path() method, The $path to log directory should contain a value.');

        Logger::init()
            ->log('test');
    }

    public function testCanLogDataInNormalLog()
    {
        $data = 'test';

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $filePath = $this->getLogFilePath('log.log');

        $this->assertFileExists($filePath);
        $this->assertFileContains($filePath, $data);
    }

    public function testCanChangeTheLogFileNameInNormalLog()
    {
        $data = 'test';

        $level = $this->str_random();

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data, $level);

        $filePath = $this->getLogFilePath($level . '.log');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeThePathInNormalLog()
    {
        $data = 'test';

        $path = $this->getLogsPath('tmp');

        Logger::init()
            ->path($path)
            ->log($data);

        $filePath = $this->getLogFilePath('log.log', 'dump','tmp');

        $this->assertFileExists($filePath);
    }

    public function testCanChangeTheDirectoryNameInNormalLog()
    {
        $data = 'test';

        Logger::init()
            ->dir('example')
            ->path($this->getLogsPath())
            ->log($data);

        $filePath = $this->getLogFilePath('log.log', 'example');

        $this->assertFileExists($filePath);
    }

    public function testCanLogDailyInNormalLog()
    {
        $data = 'test';

        Logger::init()
            ->daily()
            ->path($this->getLogsPath())
            ->log($data);

        $fileName = 'log-'. date('Y-m-d').'.log';

        $filePath = $this->getLogFilePath($fileName);

        $this->assertFileExists($filePath);
    }

    public function testCanLogStringInNormalLog()
    {
        $data = $this->str_random(7);

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $this->assertFileContains($this->getLogFilePath('log.log'), $data);
    }

    public function testCanLogIntegerInNormalLog()
    {
        $data = rand(111111111, 9999999999);

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $this->assertFileContains($this->getLogFilePath('log.log'), $data);
    }

    public function testCanLogArrayInNormalLog()
    {
        $data = ['1', '2', '3', '4'];

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $log = 'array:4 [
  0 => "1"
  1 => "2"
  2 => "3"
  3 => "4"
]';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }

    public function testCanLogAssociativeArrayInNormalLog()
    {
        $data = [
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $log = 'array:3 [
  "foo" => "bar"
  "tar" => "go"
  "zee" => "lorem"
]';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }

    public function testCanLogMultiDimensionalArrayInNormalLog()
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

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

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

    public function testCanLogStdObjectInNormalLog()
    {
        $data = (object)[
            'foo' => 'bar',
            'tar' => 'go',
            'zee' => 'lorem',
        ];

        Logger::init()
            ->path($this->getLogsPath())
            ->log($data);

        $log = '+"foo": "bar"
  +"tar": "go"
  +"zee": "lorem"
}';

        $this->assertFileContains($this->getLogFilePath('log.log'), $log);
    }
}
