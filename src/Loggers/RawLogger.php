<?php

namespace SaliBhdr\DumpLog\Loggers;

use SaliBhdr\DumpLog\Contracts\ChangeableDumperLoggerInterface;
use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Exceptions\RuntimeException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;

class RawLogger implements ChangeableDumperLoggerInterface
{
    /**
     * If set to true it will create separate file each day with date suffix
     *
     * @var bool
     */
    protected $isDaily = false;

    /**
     * Base path of the directory that the log files should be saved
     *
     * @var string
     */
    protected $path;

    /**
     * The name of the logs directory
     *
     * 'efl' stand for eye friendly log
     *
     * @var string
     */
    protected $dir = 'dump';

    /**
     * The permission of the log directory for first time creation
     *
     * @var int|string
     */
    protected $permission = 0775;

    /**
     * @var AbstractDumper
     */
    protected $dumper;

    /**
     * @var string
     */
    protected $extension;

    /**
     * If set to true the logger will not throw error and simply will return boolean as result
     *
     * This is useful when you don't want the logger to interfere in the code execution and throw exception
     *
     * @var bool
     */
    protected $silent = false;

    /**
     * @param mixed $data
     *
     * @return bool
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function emergency($data): bool
    {
        return $this->log($data, 'emergency');
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
        return $this->log($data, 'alert');
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
        return $this->log($data, 'critical');
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
        return $this->log($data, 'error');
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
        return $this->log($data, 'warning');
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
        return $this->log($data, 'notice');
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
        return $this->log($data, 'info');
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
        return $this->log($data, 'debug');
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
        $data = [
            'class'   => get_class($e),
            'massage' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        if ($withTrace) {
            $data['trace'] = $e->getTrace();
        }

        return $this->log($data, 'exception');
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
        if (!$this->silent) {
            $this->save($data, $level);

            return true;
        }

        try {
            $this->save($data, $level);

            return true;
        } catch (\Throwable $e) {
        }

        return false;
    }

    /**
     * @param mixed  $data
     * @param string $level
     * @param bool   $makeDir
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function save($data, string $level = 'log', bool $makeDir = true): void
    {
        if (empty($this->dumper) || empty($this->extension)) {
            throw new InvalidArgumentException('Please specify a dumper and file extension with dumper() method.');
        }

        $fullPath = $this->getFullPath();

        if ($makeDir) {
            $this->makeDirIfNotExists($fullPath);
        }

        if ($this->isDaily) {
            $level .= '-' . date('Y-m-d');
        }

        $file = $fullPath . DIRECTORY_SEPARATOR . "$level.$this->extension";

        $cloner = new VarCloner();

        $output = @fopen($file, 'a+b');

        if (!$output || !is_resource($output)) {
            throw new RuntimeException("Output should be a resource, The directory `$fullPath` not exists. Maybe something went wrong with log file creation.");
        }

        fwrite($output, $this->getLogTitle());

        $this->dumper->dump($cloner->cloneVar($data), $output);
    }

    /**
     * @param string $path
     */
    protected function makeDirIfNotExists(string $path): void
    {
        if (!is_dir($path)) {
            $oldMask = umask(0);
            mkdir($path, $this->permission, true);
            umask($oldMask);
        }
    }

    /**
     * @return string
     */
    protected function getLogTitle(): string
    {
        $title = "\n";
        $title .= '---| ';
        $title .= date('Y-m-d H:i:s');
        $title .= ' |-------------------------------------------------------------------------------------------';
        $title .= "\n\n";

        return $title;
    }

    /**
     * Returns the full path to the log directory
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function getFullPath(): string
    {
        if (empty($this->path)) {
            throw new InvalidArgumentException('Please specify log directory location with path() method, The $path to log directory should contain a value.');
        }

        return str_replace(
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $this->path . DIRECTORY_SEPARATOR . $this->dir
        );
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
    public function path(string $path = null): DumpLoggerInterface
    {
        $this->path = $path;

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
        $this->dir = $dir;

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
        $this->permission = $permission;

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
        $this->isDaily = $isDaily;

        return $this;
    }

    /**
     * @param AbstractDumper $dumper
     * @param string         $extension
     *
     * @return $this
     */
    public function dumper(AbstractDumper $dumper, string $extension): ChangeableDumperLoggerInterface
    {
        $this->dumper    = $dumper;
        $this->extension = $extension;

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
        $this->silent = $silent;

        return $this;
    }
}
