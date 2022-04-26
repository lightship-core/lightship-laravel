<?php

namespace Lightship\Contracts;

use Lightship\Report;
use Lightship\Route;

interface Lightship
{
    public function domain(string $domain): static;

    public function route(string $path, array $queries): static;

    public function analyse(): void;

    public function onReportedRoute(Route $route, Report $report): void;

    public function toJson(): string;

    public function toPrettyJson(): string;

    public function toArray(): array;
}
