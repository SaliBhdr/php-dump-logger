<?php

namespace SaliBhdr\DumpLog\Contracts;

interface LoggerInterface
{
    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function emergency($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function alert($data): bool;
    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function critical($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function error($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function warning($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function notice($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function info($data): bool;

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function debug($data): bool;

    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    public function exception(\Throwable $e): bool;

    /**
     * @param mixed  $data
     * @param string $level
     *
     * @return bool
     */
    public function log($data, string $level): bool;
}
