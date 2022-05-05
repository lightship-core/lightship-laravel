<?php

namespace Lightship\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Lightship\Facades\Lightship;
use Lightship\Report;
use Lightship\Result;
use Lightship\Route as LightshipRoute;
use Lightship\RuleType;

class LightshipRun extends Command
{
    protected $signature = 'lightship:run {--r|route=* : The route to scan.} {--u|url=* : The URL to scan.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan your routes and URLs.';

    protected int $passed = 0;
    protected int $failed = 0;
    protected float $totalDurationInSeconds = 0.0;

    public function handle(): int
    {
        $routes = $this->option("route");
        $urls = $this->option("url");

        if (is_string($routes)) {
            $routes = [$routes];
        }

        if (is_string($urls)) {
            $urls = [$urls];
        }

        assert(is_array($routes));
        assert(is_array($urls));

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

        $this->displaySummary();

        return 0;
    }

    protected function report(LightshipRoute $route, Report $report): void
    {
        $lines = [
            "",
            $route->path(),
        ];

        $lines = static::addScoreLines($route, $report, $lines);
        $lines = static::addResultLines($route, $report, $lines);

        $this->stackSummary($report);

        foreach ($lines as $line) {
            $this->line($line);
        }
    }

    /**
     * @param array<string> $lines
     *
     * @return array<string>
     */
    protected static function addScoreLines(LightshipRoute $route, Report $report, array $lines): array
    {
        $updatedLines = $lines;

        $ruleTypes = static::ruleTypes();

        foreach ($ruleTypes as $ruleType) {
            $ruleName = static::ruleTypeName($ruleType);
            $score = str_pad((string) $report->score($ruleType), 3, " ", STR_PAD_LEFT);

            $updatedLines[] = "  $ruleName $score";
        }

        return $updatedLines;
    }

    /**
     * @param array<string> $lines
     *
     * @return array<string>
     */
    protected function addResultLines(LightshipRoute $route, Report $report, array $lines): array
    {
        $updatedLines = $lines;

        $ruleTypes = static::ruleTypes();

        foreach ($ruleTypes as $ruleType) {
            $results = $report->results($ruleType);
            $ruleName = static::ruleTypeName($ruleType);

            $updatedLines[] = "";
            $updatedLines[] = "  $ruleName";

            foreach ($results as $result) {
                $state = $result->passes ? "✔️ " : "❌";

                $updatedLines[] = "    $state {$result->name}";
            }
        }

        return $updatedLines;
    }

    protected function stackSummary(Report $report): void
    {
        $passed = collect(static::ruleTypes())
            ->filter(
                fn (RuleType $ruleType): bool =>
                collect($report->results($ruleType))
                    ->filter(fn (Result $result): bool => !$result->passes)
                    ->isNotEmpty()
            )
            ->isEmpty();

        if ($passed) {
            $this->passed++;
        } else {
            $this->failed++;
        }

        $this->totalDurationInSeconds += $report->durationInSeconds;
    }

    protected function displaySummary(): void
    {
        $total = $this->passed + $this->failed;
        $totalDurationInSeconds = round($this->totalDurationInSeconds, 2);

        $this->line("");
        $this->line("Passed   {$this->passed}");
        $this->line("Failed   {$this->failed}");
        $this->line("Total    $total");
        $this->line("Duration {$totalDurationInSeconds} sec.");
    }

    /**
     * @return array<RuleType>
     */
    protected static function ruleTypes(): array
    {
        return [
            RuleType::Accessibility,
            RuleType::Performance,
            RuleType::Security,
            RuleType::Seo,
        ];
    }

    protected static function ruleTypeName(RuleType $ruleType): string
    {
        return str_pad(match ($ruleType) {
            RuleType::Accessibility => "accessibility",
            RuleType::Performance => "performance",
            RuleType::Security => "security",
            RuleType::Seo => "seo",
            default => "unknown",
        }, 13);
    }
}
