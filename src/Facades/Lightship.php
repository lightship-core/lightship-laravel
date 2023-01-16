<?php

namespace Lightship\Facades;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Facade;
use Lightship\Contracts\Lightship as LightshipContract;
use Lightship\Lightship as BaseLightship;

/**
 * @method static BaseLightship client(Client $client)
 * @method static BaseLightship onReportedRoute(Closure $callback)
 * @method route(string $path, array $queries = [])
 */
final class Lightship extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LightshipContract::class;
    }
}
