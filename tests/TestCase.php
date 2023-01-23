<?php

namespace SaliBhdr\DumpLog\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
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

    public function assertFileContains(string $path, string $phrase): void
    {
        $content = @file_get_contents($path);

        if (false === $content || is_null($content) || '' === $content) {
            $this->assertTrue(false, 'The does not have any content');
        }

        $this->assertTrue(strpos($content, $phrase) !== false, "Target file does not contains `$phrase`");
    }

    protected function strRandom(int $chars = 10): string
    {
        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $chars; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    protected function assertFilePermission(string $path, string $expected): void
    {
        $actual = $this->getFilePermission($path);

        $this->assertEquals($expected, $actual, "'The directory '$path' permission expected to be $expected but actual is $actual'");
    }

    protected function getFilePermission(string $path): string
    {
        return substr(
            sprintf(
                '%o',
                fileperms($path)
            ),
            -4
        );
    }

    protected function callNotPublicMethod($class, string $method, array $args)
    {
        if (is_object($class)) {
            $stringClass = get_class($class);
        } else {
            $stringClass = $class;
        }

        $reflectionClass = new \ReflectionClass($stringClass);

        $method = $reflectionClass->getMethod($method);

        $method->setAccessible(true);

        return $method->invokeArgs($class, $args);
    }
}
