<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImpactMetric extends Model
{
  protected $fillable = [
    'icon',
    'title',
    'metric',
    'description',
    'is_published',
    'sort_order'
  ];

  protected $casts = [
    'is_published' => 'boolean',
    'metric' => 'string',
    'sort_order' => 'integer'
  ];

  public function histories(): HasMany
  {
    return $this->hasMany(MetricHistory::class);
  }

  public function recordHistory(?string $notes = null): void
  {
    $this->histories()->create([
      'value' => (int) str_replace(',', '', $this->metric),
      'recorded_at' => now(),
      'notes' => $notes
    ]);
  }

  public function getLatestTrend(): float
  {
    $recentHistories = $this->histories()
      ->orderBy('recorded_at', 'desc')
      ->limit(2)
      ->get();

    if ($recentHistories->count() < 2) {
      return 0;
    }

    $latest = $recentHistories->first()->value;
    $previous = $recentHistories->last()->value;

    if ($previous == 0) {
      return $latest > 0 ? 100 : 0;
    }

    return (($latest - $previous) / $previous) * 100;
  }

  protected static function booted(): void
  {
    static::updated(function ($metric) {
      // Automatically record history when metric value changes
      if ($metric->isDirty('metric')) {
        $metric->recordHistory();
      }
    });
  }
}
