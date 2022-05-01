<?php

namespace Lightship\Contracts;

use Lightship\Report;
use Lightship\Route;

interface Lightship
{
    public function domain(string $domain): static;

    /**
     * @param array<string, string> $queries
     */
    public function route(string $path, array $queries): static;

    public function analyse(): void;

    public function onReportedRoute(Route $route, Report $report): void;

    public function toJson(): string;

    public function toPrettyJson(): string;

    /**
     * @return array{url: string, durationInSeconds: float, scores: array{seo: int, security: int, performance: int, accessibility: int}, seo: array<array{name: string, passes: bool}>, security: array<array{name: string, passes: bool}>, performance: array<array{name: string, passes: bool}>, accessibility: array<array{name: string, passes: bool}>}
     */
    public function toArray(): array;
}
