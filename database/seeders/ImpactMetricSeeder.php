<?php

namespace Database\Seeders;

use App\Models\ImpactMetric;
use Illuminate\Database\Seeder;

class ImpactMetricSeeder extends Seeder
{
  public function run(): void
  {
    $metrics = [
      [
        'icon' => 'users',
        'title' => 'Individuals Reached',
        'metric' => '55000',
        'description' => 'People reached directly or indirectly impacted across Malawi',
        'sort_order' => 1,
      ],
      [
        'icon' => 'handshake',
        'title' => 'Stakeholders Engaged',
        'metric' => '2576',
        'description' => 'Citizen groups and key vulnerable populations supported',
        'sort_order' => 2,
      ],
      [
        'icon' => 'medical',
        'title' => 'Mobile & Camp Courts',
        'metric' => '876',
        'description' => 'Survivors and key vulnerable populations served',
        'sort_order' => 3,
      ],
      [
        'icon' => 'water',
        'title' => 'Governance Projects',
        'metric' => '120',
        'description' => 'CSOs and CBOs supported to improve civic space and good governance',
        'sort_order' => 4,
      ],
      [
        'icon' => 'training',
        'title' => 'Training Sessions',
        'metric' => '257',
        'description' => 'Skill development workshops supported and facilitated',
        'sort_order' => 5,
      ],
      [
        'icon' => 'women',
        'title' => 'Women Empowered',
        'metric' => '15000',
        'description' => 'Female participants in our programs',
        'sort_order' => 6,
      ],
      [
        'icon' => 'volunteers',
        'title' => 'Volunteers',
        'metric' => '973',
        'description' => 'Community service rendered across Malawi',
        'sort_order' => 7,
      ]
    ];

    foreach ($metrics as $index => $metric) {
      ImpactMetric::create($metric);
    }
  }
}
