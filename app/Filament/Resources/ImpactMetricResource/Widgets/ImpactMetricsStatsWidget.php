<?php

namespace App\Filament\Resources\ImpactMetricResource\Widgets;

use App\Models\ImpactMetric;
use App\Models\MetricHistory;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ImpactMetricsStatsWidget extends BaseWidget
{
  protected function getStats(): array
  {
    $metrics = ImpactMetric::where('is_published', true)
      ->orderBy('sort_order')
      ->get();

    $stats = [];

    foreach ($metrics as $metric) {
      $icon = $this->getIconForMetric($metric->icon);
      $color = $this->getColorForMetric($metric->icon);
      $chartData = $this->getHistoricalData($metric->id);
      $trend = $this->calculateTrend($chartData);

      $stats[] = Stat::make($metric->title, number_format($metric->metric))
        ->description($metric->description)
        ->icon($icon)
        ->color($color)
        ->chart($chartData)
        ->chartColor($this->getTrendColor($trend));
    }

    // Add a summary stat
    $totalMetrics = $metrics->count();
    $totalGrowth = $this->calculateTotalGrowth();

    $stats[] = Stat::make('Total Metrics', $totalMetrics)
      ->description("Total growth: {$totalGrowth}%")
      ->descriptionIcon('heroicon-m-chart-bar')
      ->color('gray')
      ->chart($this->getOverallTrendData());

    return $stats;
  }

  private function getIconForMetric(string $iconType): string
  {
    return match ($iconType) {
      'users' => 'heroicon-m-users',
      'handshake' => 'heroicon-m-hand-raised',
      'medical' => 'heroicon-m-heart',
      'water' => 'heroicon-m-beaker',
      'training' => 'heroicon-m-academic-cap',
      'women' => 'heroicon-m-user-group',
      'volunteers' => 'heroicon-m-hand-thumb-up',
      default => 'heroicon-m-chart-bar',
    };
  }

  private function getColorForMetric(string $iconType): string
  {
    return match ($iconType) {
      'users' => 'success',
      'handshake' => 'warning',
      'medical' => 'danger',
      'water' => 'info',
      'training' => 'primary',
      'women' => 'purple',
      'volunteers' => 'orange',
      default => 'gray',
    };
  }

  private function getHistoricalData(int $metricId): array
  {
    // Get historical data for the last 7 periods (weeks/months)
    $historicalData = MetricHistory::where('impact_metric_id', $metricId)
      ->where('recorded_at', '>=', Carbon::now()->subWeeks(7))
      ->orderBy('recorded_at')
      ->pluck('value')
      ->toArray();

    // If we don't have enough historical data, pad with current value
    if (count($historicalData) < 7) {
      $currentMetric = ImpactMetric::find($metricId);
      $currentValue = (int) str_replace(',', '', $currentMetric->metric);

      // Fill missing data points with interpolated values
      $missingPoints = 7 - count($historicalData);
      $baseValue = $historicalData[0] ?? $currentValue * 0.8; // Start from 80% of current if no history

      for ($i = 0; $i < $missingPoints; $i++) {
        $interpolatedValue = $baseValue + (($currentValue - $baseValue) / $missingPoints) * $i;
        array_unshift($historicalData, (int) $interpolatedValue);
      }
    }

    // Ensure we have exactly 7 data points
    return array_slice($historicalData, -7);
  }

  private function calculateTrend(array $data): float
  {
    if (count($data) < 2) {
      return 0;
    }

    $firstValue = $data[0];
    $lastValue = end($data);

    if ($firstValue == 0) {
      return $lastValue > 0 ? 100 : 0;
    }

    return (($lastValue - $firstValue) / $firstValue) * 100;
  }

  private function getTrendColor(float $trend): string
  {
    if ($trend > 10) {
      return 'success';
    } elseif ($trend > 0) {
      return 'warning';
    } elseif ($trend < -10) {
      return 'danger';
    } else {
      return 'gray';
    }
  }

  private function calculateTotalGrowth(): string
  {
    $metrics = ImpactMetric::where('is_published', true)->get();
    $totalTrend = 0;
    $count = 0;

    foreach ($metrics as $metric) {
      $chartData = $this->getHistoricalData($metric->id);
      $trend = $this->calculateTrend($chartData);
      $totalTrend += $trend;
      $count++;
    }

    if ($count == 0) {
      return '0';
    }

    $averageTrend = $totalTrend / $count;
    return number_format($averageTrend, 1);
  }

  private function getOverallTrendData(): array
  {
    // Get aggregated data across all metrics for the last 7 periods
    $overallData = [];

    for ($i = 6; $i >= 0; $i--) {
      $date = Carbon::now()->subWeeks($i);
      $totalValue = MetricHistory::whereDate('recorded_at', $date->toDateString())
        ->sum('value');

      $overallData[] = $totalValue ?: 0;
    }

    // If no historical data, create a simple upward trend
    if (array_sum($overallData) == 0) {
      $currentTotal = ImpactMetric::where('is_published', true)
        ->get()
        ->sum(function ($metric) {
          return (int) str_replace(',', '', $metric->metric);
        });

      $baseValue = $currentTotal * 0.7;
      for ($i = 0; $i < 7; $i++) {
        $overallData[$i] = $baseValue + (($currentTotal - $baseValue) / 6) * $i;
      }
    }

    return $overallData;
  }

  protected function getColumns(): int
  {
    $metricsCount = ImpactMetric::where('is_published', true)->count();

    // Adjust columns based on number of metrics
    if ($metricsCount <= 2) {
      return 2;
    } elseif ($metricsCount <= 4) {
      return 4;
    } else {
      return 3;
    }
  }
}
