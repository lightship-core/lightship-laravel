# lightship-laravel

Laravel wrapper to use Lightship PHP.

## Summary

- [About](#about)
- [Features](#features)
- [Installation](#installation)
- [Examples](#examples)
- [Tests](#tests)

## About

Lightship is a way to get web page audits without using a headless browser.

This package just a wrapper around the PHP implementation of Lightship (https://github.com/lightship-core/lightship-php).

## Features

- Provides a Facade to easily use Lightship PHP

## Installation

On your terminal, install the package:

```bash
composer require --dev lightship-core/lightship-laravel
```

## Examples

- [1. Using the facade](#1-using-the-facade)
- [2. Using the comand](#2-using-the-comand)
- [3. Show failed/passed rules when using the command](#3-show-failedpassed-rules-when-using-the-command)

### 1. Using the facade

In this example, we will generate an array report using the Laravel facade.

```php
namespace App\Controllers;

use Lightship\Facades\Lightship;

class HomeController extends Controller
{
  public function index()
  {
    $report = Lightship::route("https://example.com")
      ->analyse()
      ->toArray();

    return view("home.index", [
      "report" => $report,
    ]);
  }
}
```

### 2. Using the comand

In this example, we will call Lightship from the command.

```bash
php artisan lightship:run --url https://example.com
```

You can also pass a route name.

```bash
php artisan lightship:run --route home.index
```

You can even pass multiple routes.

```bash
php artisan lightship:run --route home.index --route contact-us.index
```

And you can mix both.

```bash
php artisan lightship:run --route home.index --route contact-us.index --url https://example.com --url https://google.com
```

Since the command do not support passing query strings, you can do it by creating your own command and call this one.

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class MyCommand extends Command
{
  protected $signature = 'my-command:run';

  protected $description = 'Scan my routes.';

  public function handle()
  {
    $this->call("lightship:run", [
      "--url" => [
        route("home.index", ["lang" => "en"]),
        route("contact-us.index", ["theme" => "dark"]),
      ]
    ]);
  }
}
```

### 3. Show failed/passed rules when using the command

By default, the command does not show the failed/passed rules of each URLs to save some space. If you want to show the detail, use the `--detailed` option.

```bash
php artisan lightship:run --route home.index --detailed
```

Or by calling `Artisan::call`:

```php
use Illuminate\Support\Facades\Artisan;

// ...

Artisan::call("lightship:run", [
  "--route" => "home.index",
  "--detailed" => true,
]);
```

## Tests

```
composer check-platform-reqs
composer install
composer run analyse
composer run test
composer run lint
composer run scan
composer run check
composer run updates
```

Or

```bash
composer run all
```
