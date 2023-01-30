<?php

namespace SaliBhdr\DumpLog\Contracts;

interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function emergency($data): bool;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function alert($data): bool;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function critical($data): bool;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function error($data): bool;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function warning($data): bool;

    /**
     * Normal but significant events.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function notice($data): bool;

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function info($data): bool;

    /**
     * Detailed debug information.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function debug($data): bool;

    /**
     * For logging exceptions in try catch block
     *
     * @param \Throwable $e
     *
     * @return bool
     */
    public function exception(\Throwable $e): bool;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $data
     * @param string $level
     *
     * @return bool
     */
    public function log($data, string $level): bool;
}
