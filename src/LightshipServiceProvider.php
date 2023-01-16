<?php

namespace Lightship;

use Illuminate\Support\ServiceProvider;
use Lightship\Commands\LightshipRun;
use Lightship\Contracts\Lightship;
use Lightship\Lightship as BaseLightship;

final class LightshipServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LightshipRun::class,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Lightship::class, fn ($app): BaseLightship => new BaseLightship());
    }
}
