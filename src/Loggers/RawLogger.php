<?php

namespace SaliBhdr\DumpLog\Loggers;

use SaliBhdr\DumpLog\Contracts\DumperStrategyInterface;
use SaliBhdr\DumpLog\Contracts\DumpLoggerAwareInterface;
use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use SaliBhdr\DumpLog\Exceptions\InvalidArgumentException;
use SaliBhdr\DumpLog\Exceptions\RuntimeException;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class RawLogger implements DumpLoggerAwareInterface
{
    /**
     * @var bool
     */
    protected $isDaily = false;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $dir = 'dump';

    /**
     * @var int
     */
    protected $permission = 0775;

    /**
     * @var DumperStrategyInterface
     */
    protected $dumper;

    /**
     * @var bool
     */
    protected $silent = false;

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function critical($data): bool
    {
        return $this->log($data, 'critical');
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function error($data): bool
    {
        return $this->log($data, 'error');
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function info($data): bool
    {
        return $this->log($data, 'info');
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function debug($data): bool
    {
        return $this->log($data, 'debug');
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
    protected function save($data, string $level, bool $makeDir = true): void
    {
        if (empty($this->dumper)) {
            throw new InvalidArgumentException('Please specify a dumper strategy with dumper() method.');
        }

        $fullPath = $this->getFullPath();

        if ($makeDir) {
            $this->makeDirIfNotExists($fullPath);
        }

        if ($this->isDaily) {
            $level .= '-' . date('Y-m-d');
        }

        $file = $fullPath . DIRECTORY_SEPARATOR . $level . '.' . $this->dumper->getExtension();

        $cloner = new VarCloner();

        $output = @fopen($file, 'a+b');

        if (!$output || !is_resource($output)) {
            throw new RuntimeException("Output should be a resource, The directory `$fullPath` not exists. Maybe something went wrong with log file creation.");
        }

        fwrite($output, $this->dumper->getTitle());

        $this->dumper
            ->getDumper()
            ->dump($cloner->cloneVar($data), $output);
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

        # replaces double dir separator with single dir separator
        return str_replace(
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $this->path . DIRECTORY_SEPARATOR . $this->dir
        );
    }

    /** {@inheritDoc} */
    public function path(string $path): DumpLoggerInterface
    {
        $this->path = $path;

        return $this;
    }

    /** {@inheritDoc} */
    public function dir(string $dir): DumpLoggerInterface
    {
        $this->dir = $dir;

        return $this;
    }

    /** {@inheritDoc} */
    public function permission(int $permission): DumpLoggerInterface
    {
        $this->permission = $permission;

        return $this;
    }

    /** {@inheritDoc} */
    public function daily(bool $isDaily = true): DumpLoggerInterface
    {
        $this->isDaily = $isDaily;

        return $this;
    }

    /** {@inheritDoc} */
    public function silent(bool $silent = true): DumpLoggerInterface
    {
        $this->silent = $silent;

        return $this;
    }

    /** {@inheritDoc} */
    public function dumper(DumperStrategyInterface $dumper): DumpLoggerAwareInterface
    {
        $this->dumper = $dumper;

        return $this;
    }
}
