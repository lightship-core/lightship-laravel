<?php

namespace Tests;

use Lightship\LightshipServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            LightshipServiceProvider::class,
        ];
    }
}
