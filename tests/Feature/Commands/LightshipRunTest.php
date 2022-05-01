<?php

namespace Tests\Feature\Facades;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\PendingCommand;
use Lightship\Commands\LightshipRun;
use Lightship\Facades\Lightship;
use Tests\TestCase;

final class LightshipRunTest extends TestCase
{
    use WithFaker;

    public function testCanRunOnOneUrl(): void
    {
        $client = new Client([
            "handler" => HandlerStack::create(new MockHandler([
                new Response(200, [], "")
            ]))
        ]);

        Lightship::client($client);

        $url = $this->faker->url();

        $command = $this->artisan("lightship:run", [
            "--url" => $url,
        ]);

        assert($command instanceof PendingCommand);

        $command->assertSuccessful()
            ->expectsOutputToContain($url)
            ->expectsOutputToContain("  accessibility  38")
            ->expectsOutputToContain("  performance    50")
            ->expectsOutputToContain("  security       50")
            ->expectsOutputToContain("  seo             0")
            ->expectsOutputToContain("")
            ->expectsOutput("  accessibility")
            ->expectsOutput("    ❌ metaViewportPresent")
            ->expectsOutput("    ❌ useLandmarkTags")
            ->expectsOutput("    ✔️  buttonsAndLinksUseAccessibleName")
            ->expectsOutput("    ✔️  idsAreUnique")
            ->expectsOutput("    ✔️  imagesHaveAltAttributes")
            ->expectsOutput("    ❌ doctypeHtmlPresent")
            ->expectsOutput("    ❌ metaThemeColorPresent")
            ->expectsOutput("  performance  ")
            ->expectsOutput("    ❌ textCompressionEnabled")
            ->expectsOutput("    ✔️  noRedirects")
            ->expectsOutput("    ✔️  fastResponseTime")
            ->expectsOutput("    ❌ usesHttp2")
            ->expectsOutput("  security     ")
            ->expectsOutput("    ❌ xFrameOptionsPresent")
            ->expectsOutput("    ❌ strictTransportSecurityHeaderPresent")
            ->expectsOutput("    ✔️  serverHeaderHidden")
            ->expectsOutput("    ✔️  xPoweredByHidden")
            ->expectsOutput("  seo          ")
            ->expectsOutput("    ❌ titlePresent")
            ->expectsOutput("    ❌ langPresent")
            ->expectsOutput("    ❌ linksDefineHref")
            ->expectsOutput("    ❌ metaDescriptionPresent");
    }

    public function testRunOnOneRoute(): void
    {
        $client = new Client([
            "handler" => HandlerStack::create(new MockHandler([
                new Response(200, [], "")
            ]))
        ]);

        Lightship::client($client);

        $command = $this->artisan(LightshipRun::class, [
            "--route" => "contact-us.index",
        ]);

        assert($command instanceof PendingCommand);

        $command->assertSuccessful();
    }

    public function testRaiseAnErrorIfUrlIsNotValid(): void
    {
        $command = $this->artisan("lightship:run", [
            "--url" => [
                $this->faker->text(),
            ]
        ]);

        assert($command instanceof PendingCommand);

        $command->assertExitCode(2);
    }

    public function testRaiseErrorIfRouteNotFound(): void
    {
        $command = $this->artisan("lightship:run", [
            "--route" => [
                $this->faker->slug(1),
            ]
        ]);

        assert($command instanceof PendingCommand);

        $command->assertExitCode(1);
    }
}
