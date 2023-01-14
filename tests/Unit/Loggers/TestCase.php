<?php

namespace SaliBhdr\DumpLog\Tests\Unit\Loggers;

use SaliBhdr\DumpLog\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        $this->removeDir($this->getLogsPath('logs'));
        $this->removeDir($this->getLogsPath('tmp'));

        parent::tearDown();
    }

    protected function tearDown()
    {
        $this->removeDir($this->getLogsPath('logs'));
        $this->removeDir($this->getLogsPath('tmp'));

        parent::tearDown();
    }

    protected function getLogsPath(string $pathName = 'logs'): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $pathName;
    }

    protected function getLogsDir(string $dirName = 'dump', string $pathName = 'logs'): string
    {
        return $this->getLogsPath($pathName) . DIRECTORY_SEPARATOR . $dirName;
    }

    protected function getLogFilePath(string $filename, string $dirName = 'dump', string $pathName = 'logs'): string
    {
        return $this->getLogsDir($dirName, $pathName) . DIRECTORY_SEPARATOR . $filename;
    }
}
