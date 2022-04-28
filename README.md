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

- [Using the facade](#using-the-facade)
- [Using the comand](#using-the-comand)

### Using the facade

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

### Using the comand

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

Since the command do not support passing query strings, you can do it by using a "lightship.json" configuration file. If you call the command without any argument, the command will try to find this configuration file to run.

If this configuration file is not located on your root directory, you can specify a custom path.

```bash
php artisan lightship:run --config storage/lightship.json
```

You can't use route names in it. To tackle this issue, you can fallback to create your own command and call this one.

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

## Tests

```
composer run test
```
