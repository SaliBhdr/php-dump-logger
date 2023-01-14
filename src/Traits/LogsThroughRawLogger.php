<?php

namespace SaliBhdr\DumpLog\Traits;

use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Exceptions\RuntimeException;

trait LogsThroughRawLogger
{
    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function emergency($data): bool
    {
        return $this->logger->emergency($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function alert($data): bool
    {
        return $this->logger->alert($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function critical($data): bool
    {
        return $this->logger->critical($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function error($data): bool
    {
        return $this->logger->error($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function warning($data): bool
    {
        return $this->logger->warning($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function notice($data): bool
    {
        return $this->logger->notice($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function info($data): bool
    {
        return $this->logger->info($data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function debug($data): bool
    {
        return $this->logger->debug($data);
    }

    /**
     * @param \Throwable $e
     * @param bool       $withTrace
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function exception(\Throwable $e, bool $withTrace = false): bool
    {
        return $this->logger->exception($e, $withTrace);
    }

    /**
     * @param mixed  $data
     * @param string $level
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function log($data, string $level = 'log'): bool
    {
        return $this->logger->log($data, $level);
    }

    /**
     * Changes the base path of the directory that the log files should be saved
     *
     * Default value is global server variable's 'DOCUMENT_ROOT' if exists or current directory which the logger is called
     *
     * @param string $path
     *
     * @return $this
     */
    public function path(string $path): DumpLoggerInterface
    {
        $this->logger->path($path);

        return $this;
    }

    /**
     * Changes the name of the logs directory
     *
     * Default directory name is dump
     *
     * @param string $dir
     *
     * @return $this
     */
    public function dir(string $dir): DumpLoggerInterface
    {
        $this->logger->dir($dir);

        return $this;
    }

    /**
     * Changes the permission of the log directory
     *
     * Default is 0770 (all access to owner and group)
     *
     * @param int $permission
     *
     * @return $this
     */
    public function permission(int $permission): DumpLoggerInterface
    {
        $this->logger->permission($permission);

        return $this;
    }

    /**
     * If set to true it will create separate file each day with date suffix
     *
     * @param bool $isDaily
     *
     * @return $this
     */
    public function daily(bool $isDaily = true): DumpLoggerInterface
    {
        $this->logger->daily($isDaily);

        return $this;
    }

    /**
     * If set to true the logger will not throw error and simply will return boolean as result
     *
     * This is useful when you don't want the logger to interfere in the code execution and throw exception
     *
     * @param bool $silent
     *
     * @return $this
     */
    public function silent(bool $silent = true): DumpLoggerInterface
    {
        $this->logger->silent($silent);

        return $this;
    }
}
