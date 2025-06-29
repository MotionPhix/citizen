<?php

namespace App\Console\Commands;

use App\Models\ImpactMetric;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PopulateMetricHistory extends Command
{
  protected $signature = 'metrics:populate-history';
  protected $description = 'Populate initial historical data for impact metrics';

  public function handle(): void
  {
    $this->info('Populating metric history...');

    $metrics = ImpactMetric::all();

    foreach ($metrics as $metric) {
      $currentValue = (int) str_replace(',', '', $metric->metric);

      // Create 7 weeks of historical data with realistic growth
      for ($i = 7; $i >= 1; $i--) {
        $date = Carbon::now()->subWeeks($i);

        // Calculate a realistic historical value (80-95% of current value)
        $growthFactor = 0.8 + (0.15 * (7 - $i) / 7); // Gradual growth from 80% to 95%
        $historicalValue = (int) ($currentValue * $growthFactor);

        // Add some random variation (±5%)
        $variation = rand(-5, 5) / 100;
        $historicalValue = (int) ($historicalValue * (1 + $variation));

        $metric->histories()->create([
          'value' => $historicalValue,
          'recorded_at' => $date,
          'notes' => 'Initial historical data'
        ]);
      }

      // Record current value as most recent history
      $metric->recordHistory('Current value recorded');

      $this->info("Created history for: {$metric->title}");
    }

    $this->info('Metric history population completed!');
  }
}
