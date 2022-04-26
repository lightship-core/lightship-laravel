<?php

namespace Lightship;

use Illuminate\Support\ServiceProvider;
use Lightship\Contracts\Lightship;
use Lightship\Lightship as BaseLightship;

class LightshipServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Lightship::class, function ($app) {
            return new BaseLightship();
        });
    }
}
