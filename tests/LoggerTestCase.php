<?php

namespace SaliBhdr\DumpLog\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class LoggerTestCase extends BaseTestCase
{
    protected function tearDown()
    {
        $this->removeDir($this->getLogsPath('logs'));
        $this->removeDir($this->getLogsPath('tmp'));

        parent::tearDown();
    }

    protected function removeDir(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->removeDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    protected function getLogsPath(string $pathName = 'logs'): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $pathName;
    }

    protected function getLogsDir(string $dirName = 'dump', string $pathName = 'logs'): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $pathName . DIRECTORY_SEPARATOR . $dirName;
    }

    protected function getLogFilePath(string $filename, string $dirName = 'dump', string $pathName = 'logs'): string
    {
        return $this->getLogsDir($dirName, $pathName) . DIRECTORY_SEPARATOR . $filename;
    }

    public function assertFileContains(string $path, string $phrase)
    {
        $content = @file_get_contents($path);

        if (false === $content || is_null($content) || '' === $content) {
            $this->assertTrue(false, 'The does not have any content');
        }

        $this->assertTrue(strpos($content, $phrase) !== false, "Target file does not contains `$phrase`");
    }

    protected function str_random(int $chars = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $chars; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
