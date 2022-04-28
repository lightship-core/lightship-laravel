<?php

namespace Tests\Feature\Facades;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class LightshipRunTest extends TestCase
{
    use WithFaker;

    public function testCanRunOnOneUrl(): void
    {
        $url = $this->faker->url();

        $this->artisan("lightship:run", [
            "--url" => [
                $url,
            ],
        ])
            ->assertSuccessful()
            ->expectsOutput($url);
    }

    public function testRaiseAnErrorIfUrlIsNotValid(): void
    {
        $this->artisan("lightship:run", [
            "--url" => [
                $this->faker->text(),
            ]
        ])->assertExitCode(2);
    }

    public function testRaiseErrorIfRouteNotFound(): void
    {
        $this->artisan("lightship:run", [
            "--route" => [
                $this->faker->slug(1),
            ]
        ])->assertExitCode(1);
    }
}
