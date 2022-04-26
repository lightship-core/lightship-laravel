<?php

namespace Lightship\Facades;

use Illuminate\Support\Facades\Facade;
use Lightship\Contracts\Lightship as LightshipContract;

class Lightship extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LightshipContract::class;
    }
}
