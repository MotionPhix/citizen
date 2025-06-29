<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricHistory extends Model
{
  protected $fillable = [
    'impact_metric_id',
    'value',
    'recorded_at',
    'notes'
  ];

  protected $casts = [
    'recorded_at' => 'datetime',
    'value' => 'integer'
  ];

  public function impactMetric(): BelongsTo
  {
    return $this->belongsTo(ImpactMetric::class);
  }
  public function getFormattedValueAttribute(): string
  {
    return number_format($this->value);
  }

  public function getFormattedRecordedAtAttribute(): string
  {
    return $this->recorded_at->format('M j, Y');
  }

  protected static function booted(): void
  {
    static::creating(function ($history) {
      $history->recorded_at = $history->recorded_at ?: now();
    });
  }
}
