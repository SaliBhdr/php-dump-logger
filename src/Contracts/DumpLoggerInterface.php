<?php

namespace SaliBhdr\DumpLog\Contracts;

interface DumpLoggerInterface extends LoggerInterface
{
    /**
     * Changes the base path of the directory that the log files should be saved
     *
     * Default value is global server variable's 'DOCUMENT_ROOT' if exists or current directory which the logger is called
     *
     * @param string $path
     *
     * @return $this
     */
    public function path(string $path): DumpLoggerInterface;

    /**
     * Changes the name of the logs directory
     *
     * Default directory name is dump
     *
     * @param string $dir
     *
     * @return $this
     */
    public function dir(string $dir): self;

    /**
     * Changes the permission of the log directory
     *
     * Default is 0770 (all access to owner and group)
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
     * This is useful when you don't want the logger to interfere in the code execution and throw exception
     *
     * @param bool $silent
     *
     * @return $this
     */
    public function silent(bool $silent = true): self;
}
