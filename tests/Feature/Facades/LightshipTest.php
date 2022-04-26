<?php

namespace Tests\Feature\Facades;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response as LaravelResponse;
use Lightship\Facades\Lightship;
use Tests\TestCase;

final class LightshipTest extends TestCase
{
    use WithFaker;

    public function testFacadeCanBeUsedToGenerateArrayReport(): void
    {
        $url = $this->faker->url();
        $client = new Client([
            "handler" => HandlerStack::create(new MockHandler([
                new Response(LaravelResponse::HTTP_OK, [], ""),
            ]))
        ]);

        $lightship = Lightship::client($client)
            ->route($url)
            ->analyse();

        $report = $lightship->toArray();

        self::assertEquals($url, $report[0]["url"]);
    }
}
