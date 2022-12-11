# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- New `--detailed` option to show failed/pass rules for each URLs ([#11](https://github.com/lightship-core/lightship-laravel/issues/11)).
- Support for PHP 8.2.

### Fixed

- No more warning about `tests/Feature/Commands/LightshipRunTest.php` not complying with PSR-4 autoloading standard ([#10](https://github.com/lightship-core/lightship-laravel/issues/10)).

### Breaked

- `lightship:run` will not display failed/passed rules for each URLs by default ([#11](https://github.com/lightship-core/lightship-laravel/issues/11)).

## [0.3.1] 2022-05-05

### Fixed

- Failed URLs/routes will now be correctly counted as failed in the summary ([#7](https://github.com/lightship-core/lightship-laravel/issues/7)).

## [0.3.0] 2022-05-04

### Added

- Summary at the end of the command ([#4](https://github.com/lightship-core/lightship-laravel/issues/4)).

### Fixed

- This package's service provider will now be auto-discovered automatically so that you do not need to specify the service provider in your `config/app.php` file ([#3](https://github.com/lightship-core/lightship-laravel/issues/3)).

## [0.2.0] 2022-05-02

### Breaked

- This package now requires Laravel 9 ([#1](https://github.com/lightship-core/lightship-laravel/issues/1)).

## [0.1.0] 2022-05-01

### Added

- First working version.
