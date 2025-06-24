<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
