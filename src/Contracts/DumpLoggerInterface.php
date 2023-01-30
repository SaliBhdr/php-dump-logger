<?php

namespace SaliBhdr\DumpLog\Contracts;

interface DumpLoggerInterface extends LoggerInterface
{
    /**
     * Changes the base path of the directory that the log files should be saved
     *
     * @param string $path
     *
     * @return $this
     */
    public function path(string $path): self;

    /**
     * Changes the name of the logs directory
     *
     * @param string $dir
     *
     * @return $this
     */
    public function dir(string $dir): self;

    /**
     * Changes the permission of the log directory
     *
     * @param int $permission
     *
     * @return $this
     */
    public function permission(int $permission): self;

    /**
     * If set to true it will create separate file each day with date suffix
     *
     * @param bool $isDaily
     *
     * @return $this
     */
    public function daily(bool $isDaily = true): self;

    /**
     * If set to true the logger will not throw error and simply will return boolean as result
     *
     * This is useful when you want to prevent the logger from throwing an exception
     *
     * @param bool $silent
     *
     * @return $this
     */
    public function silent(bool $silent = true): self;
}
