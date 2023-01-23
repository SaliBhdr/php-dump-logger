# PHP Dump Logger
## It's All About Readability.

![SaliBhdr|typhoon][link-logo]

[![Total Downloads][ico-downloads]][link-downloads]
[![Required PHP Version][ico-php]][link-packagist]
[![Latest Versions][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Today Downloads][ico-today-downloads]][link-downloads]

[![Build Status](https://travis-ci.org/salibhdr/php-dump-logger.svg?branch=master)](https://travis-ci.org/salibhdr/php-dump-logger)
[![Coverage Status](https://coveralls.io/repos/github/salibhdr/php-dump-logger/badge.svg?branch=develop)](https://coveralls.io/github/salibhdr/php-dump-logger?branch=develop)

## Table of Contents
* [Introduction](#intoduction)
* [Installation](#installation)
* [Version Compatibility](#version-compatibility)
* [Basic Usage](#basic-usage)
* [Detailed Usage](#detailed-usage)
  * [Methods](#methods)
  * [Exception Logging](#exception-logging)
  * [Custom Log Level](#custom-log-level)
  * [Path](#path)
  * [Directory Name](#directory-name)
  * [Directory Permission](#directory-permission)
  * [Daily Log](#daily-log)
  * [Silent Logging](#silent-logging)
* [Loggers](#loggers)
    * [Pretty Logger](#pretty-logger)
    * [Html Logger](#html-logger)
    * [Raw Logger](#raw-logger)
    * [Custom Logger](#custom-logger)
* [Full Example](#full-example)
* [Issues](#issues)
* [License](#license)
* [Testing](#testing)
* [Contributing](#contributing)


## Introduction
PHP dump logger uses [Symfony's var-dumper][link-symfony-var-dumper] to create a simple, easy to use, eye-friendly, and pretty log files for any PHP application. 
If you are the fan of using `dd()` and `dump()`, this package is for you.

**Example log file content:**

![php dump logger](https://symfony.com/doc/6.2/_images/01-simple.png)

Have you ever tried to log something with Symfony's monolog or laravel's logger facade and tried to find the logged data inside a maze of text?
Specially when you don't have time and don't want to go through installing and configuring Xdebug or there is a bug in production code,
and you just want to see the API responses without messing with the code execution. 
The first solution that comes to mind is to use a logger to log the data instead of using `dd()`, `dump()`, or `var_dump()` functions. 
But as I said loggers aren't producing readable files. You have to provide them with string, and they are incapable of directly logging a complex class. 
How nice could it be to have a functionality like `dd()` without interruption in the code. 

Here **php-dump-logger** comes to the rescue.

**php-dump-logger** uses [Symfony's var-dumper][link-symfony-var-dumper] to generate the log file content and then saves the output to a file.
You can either log the data in a nice `html` or `log` format or even provide your own dumper.

**Features**

* Readable log in `html` format
* Readable log in `log` format
* Ability to log classes, arrays, objects, and basically any variable you like. There is no limit.
* Fully customization of log format and dumper
* Separate daily and all in one logs
* Logging file in a custom level
* Changing the path and directory of the log

## Installation

Install via composer:

```sh
 composer require salibhdr/php-dump-logger
```

## Version Compatibility

| PHP          | PHP Dump Logger |
|:-------------|:----------------|
| ^7.0 && ^7.1 | 1.x             |

## Basic Usage

```php
<?php

use SaliBhdr\DumpLog\Factory\Logger;

Logger::make()
        ->path('__path-to-dir__')
        ->log([
          "foo" => [
            "foo" => "bar"
            "tar" => "go"
            "zee" => "lorem"
          ]
          "tar" => [
            "foo" => "bar"
          ]
          "zee" => "lorem"
        ])
```

This will create a `__path-to-dir__/dump/log.log` file like this:

```text
---| 2023-01-18 13:37:09 |-------------------------------------------------------------------------------------------

array:3 [
  "foo" => array:3 [
    "foo" => "bar"
    "tar" => "go"
    "zee" => "lorem"
  ]
  "tar" => array:1 [
    "foo" => "bar"
  ]
  "zee" => "lorem"
]
```

## Detailed Usage

* [Methods](#methods)
* [Exception Logging](#exception-logging)
* [Custom Log Level](#custom-log-level)
* [Path](#path)
* [Directory Name](#directory-name)
* [Directory Permission](#directory-permission)
* [Daily Log](#daily-log)
* [Silent Logging](#silent-logging)

### Methods

Each log level (info, error, warning, etc.) creates a separate log file with method's name.

```php

use SaliBhdr\DumpLog\Factory\Logger;

$logger = Logger::make()
        ->path('__path-to-dir__');
        
$logger->emergency(mixed $data);
$logger->alert(mixed $data);
$logger->critical(mixed $data);
$logger->error(mixed $data);
$logger->warning(mixed $data);
$logger->notice(mixed $data);
$logger->info(mixed $data);
$logger->debug(mixed $data);
$logger->exception(\Throwable $exception);
$logger->log(mixed $data);

```

<br/>

### Exception Logging

If you want to log the exception in much more readable way You should use `exception()` method.
It is good for creating logs for exceptions like this:
If You want to see the trace of the exception you can set the second argument named `$withTrace` to true:

```php
try {

    throw new \Exception('exception message', 500);
    
} catch (\Throwable $e) {

    $logger->exception($e, true);
}
```

Output `exception.log`:

```text

---| 2023-01-18 14:01:54 |-------------------------------------------------------------------------------------------

array:5 [
  "class" => "Exception"
  "massage" => "exception message"
  "code" => 500
  "file" => "__path_to_file__"
  "line" => 356
  "trace" => array:12 [...] // appears only if $withTrace is true
]

```

<br/>

### Custom Log Level

As mentioned before each log level creates a separate log file. So you can create custom log level with custom
file name by changing the value of `$level` argument in `log()` method:

```php
   $logger->log($data, 'custom-level');
```

This will create a file named `custom-level.log`.

<br/>

### Path

By default, the path to log directory is set to be `$_SERVER['DOCUMENT_ROOT']` but if you call this logger
from a console command the document rule will be null and the logger could not find a directory to save the file, and it will throw
InvalidArgument Exception. So make sure to prove the directory path like so:

```php

$logger->path('__path-to-dir__');

```

<br/>

### Directory Name

By default, The parent directory of log files is called `dump` but you can change the directory name of the log file with `dir()` method:

```php

$logger->dir('__dir-name__');

```

You can also use this `dir()` method to arrange your log files. for example if you want to add the custom logs in the separate directory
you can do it like so:

```php
$logger->dir('dump/custom')
       ->log($data, 'custom-level');

```

<br/>

### Directory Permission

Sometimes you want to put your log files in a directory with restricted permission. 
In order to do that you can change the log file's directory permission with the `permission()` method. 
remember that This directory permission is only applied in the directory's first creation. 
So you can't change the directory's permission after the creation. 
Remember to provide the apache group with the right permissions to create and execute the file in the directory. 
You don't want to create a directory that even PHP could not write into it. 
By default, the directory and the files inside it will have 0775 permission.

```php

$logger->permission(0777);

```

<br/>

### Daily Log

Sometimes you want to log data daily into separate file's based on date, You can separate the log files daily with date suffix by calling the `daily()` method.

```php

$logger->daily()
       ->info();

```

It will create file's like this for separate days:

```text

info-2023-01-18.log
info-2023-01-19.log
info-2023-02-01.log

```

<br/>

### Silent Logging

Calling `silent()` method allows you to log data without throwing an error. 

Remember that in some cases the logger will throw an error like when the $path is empty, or when it couldn't write into the target file for permission reasons. 

So if you want to avoid that, and you don't want to interrupt the code execution, you can call `silent()` method.
In the background this method will force the logger to execute the code in try-catch block and return a boolean instead of the exception.

```php

$logger->silent(); //result will be true for success and false for failed attempt to log

```

<br/>

## Loggers

* [Pretty Logger](#pretty-logger)
* [Html Logger](#html-logger)
* [Raw Logger](#raw-logger)
* [Custom Logger](#custom-logger)


### Pretty Logger

The pretty logger is used for creating a log file with `.log` extension in a pretty readable way.

You can make the pretty logger class with logger factory class like so:

```php
<?php

use SaliBhdr\DumpLog\Factory\Logger;

$logger = Logger::make(); // the pretty logger is the default logger

// or

$logger = Logger::make('pretty');

// or

$logger = Logger::pretty();

```

<br/>

### Html Logger

The html logger is used for creating a log file with `.html` extension in a pretty readable way.
You can navigate throw different variables, toggle the multidimensional arrays and complex class properties.

You can make the html logger class with logger factory like so:

```php
<?php

use SaliBhdr\DumpLog\Factory\Logger;

$logger = Logger::make('html');

// or

$logger = Logger::html();

```

<br/>

### Raw Logger

Raw Logger is base logger class that other loggers are using it. The only difference between Raw Logger and the others is that there
is no dumper or path specified in this logger, and you have to provide a dumper and the file extension with `dumper()` and the path with `path()` method. 
Otherwise, it will throw `SaliBhdr\DumpLog\ExceptionsInvalidArgumentException`

Remember that the dumper should be the instance of `Symfony\Component\VarDumper\Dumper\AbstractDumper`.
Feel free to create your own dumper and use the Raw logger.

example:

```php
use SaliBhdr\DumpLog\Loggers\RawLogger;
use Symfony\Component\VarDumper\Dumper\CliDumper;

$dumper     = new CliDumper();
$logger     = new RawLogger();
$extension  = 'txt';

$logger->path('__path_to_dir__')
       ->dumper($dumper, $extension)
       ->log($data)

```

<br/>

### Custom Logger

You can create your own dump logger by implementing the `SaliBhdr\DumpLog\Contracts\DumpLoggerInterface` or `SaliBhdr\DumpLog\Contracts\ChangeableDumperLoggerInterface`.
You can also use the RawLogger in your own logger by first instantiating the RawLogger in your Logger and then use the `SaliBhdr\DumpLog\Traits\LogsThroughRawLogger` trait.

example:

```php
<?php

use App\CustomDumper;
use SaliBhdr\DumpLog\Contracts\DumpLoggerInterface;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class CustomTextLogger implements DumpLoggerInterface 
{
    use LogsThroughRawLogger;

    /**
     * @var RawLogger
     */
    protected $logger;

    public function __construct()
    {
        $dumper = new CustomDumper() or new CliDumper();
        $extension = 'txt';
        
        $this->logger = (new RawLogger())
            ->dumper($dumper, $extension)
            ->path('__path-to-dir__');
    }
}
```
<br />

## Full Example

```php
<?php

use SaliBhdr\DumpLog\Factory\Logger;

Logger::make()
        ->path('__path-to-dir__')
        ->daily('__custom-dir-name__')
        ->daily(true)
        ->silent(true)
        ->permission(0777)
        ->info($data);

```

## Issues

You can report issues in GitHub repository [here][link-issues]

## License

PHP dump logger is released under the MIT License.

Created by [Salar Bahador][link-github]


[![salar bahador linkedin](https://i.stack.imgur.com/gVE0j.png) LinkedIn][link-linkedin]
&nbsp;
&nbsp;
[![salar bahador github](https://i.stack.imgur.com/tskMh.png) GitHub][link-github]
&nbsp;
&nbsp;
[<img alt="salar bahador packegist" src="https://packagist.org/favicon.ico?v=1674136735" width="15"> Packagist][link-packagist]

Built with ❤ for you.

## Testing

for testing:

```sh
composer test
```

for using php code sniffer:

```sh
composer csfix
```

for both csfix and test:

```sh
composer testfix
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


[ico-php]: https://img.shields.io/badge/php-7.0|7.1-8892bf?style=flat-square&logo=php
[ico-downloads]: https://poser.pugx.org/salibhdr/php-dump-logger/downloads
[ico-today-downloads]: https://img.shields.io/packagist/dd/salibhdr/php-dump-logger.svg?style=flat-square
[ico-license]: https://poser.pugx.org/salibhdr/php-dump-logger/v/unstable
[ico-version]: https://img.shields.io/packagist/v/salibhdr/php-dump-logger.svg?style=flat-square

[link-logo]: https://drive.google.com/a/domain.com/thumbnail?id=12yntFCiYIGJzI9FMUaF9cRtXKb0rXh9X
[link-packagist]: https://packagist.org/packages/salibhdr/php-dump-logger
[link-downloads]: https://packagist.org/packages/salibhdr/php-dump-logger/stats
[link-packagist]: https://packagist.org/packages/salibhdr/php-dump-logger
[link-github]: https://github.com/Salibhdr
[link-issues]: https://github.com/Salibhdr/php-dump-logger/issues
[link-linkedin]: https://www.linkedin.com/in/salar-bahador
[link-symfony-var-dumper]: https://symfony.com/doc/current/components/var_dumper.html