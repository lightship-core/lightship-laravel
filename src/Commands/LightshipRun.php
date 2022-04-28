<?php

namespace Lightship\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Lightship\Facades\Lightship;
use Lightship\Report;
use Lightship\Route as LightshipRoute;

class LightshipRun extends Command
{
    protected $signature = 'lightship:run {--r|route=* : The route to scan.} {--u|url=* : The URL to scan.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan your routes and URLs.';

    public function handle(): int
    {
        $routes = $this->option("route");
        $urls = $this->option("url");
        $lightship = Lightship::onReportedRoute(function (LightshipRoute $route, Report $report): void {
            $this->report($route, $report);
        });

        foreach ($routes as $index => $route) {
            if (!Route::has($route)) {
                $message = "Route $route (#$index) not found.";

                report(new InvalidArgumentException($message));

                $this->error($message);

                return 1;
            }

            $lightship->route(route($route));
        }

        foreach ($urls as $index => $url) {
            if (!str_starts_with($url, "http") || filter_var($url, FILTER_VALIDATE_URL) === false) {
                $message = "The URL $url (#$index) is not a valid URL.";

                report(new InvalidArgumentException($message));

                $this->error($message);

                return 2;
            }

            $lightship->route($url);
        }

        $lightship->analyse();

        return 0;
    }

    private function report(LightshipRoute $route, Report $report): void
    {
        $this->line($route->path());
    }
}
