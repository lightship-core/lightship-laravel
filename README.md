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

## Tests

```
composer run test
```
