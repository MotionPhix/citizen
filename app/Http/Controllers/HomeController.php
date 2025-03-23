<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\View\View;

class HomeController extends Controller
{
  public function index(): View
  {
    $programs = Program::where('is_published', true)
      ->orderBy('sort_order')
      ->get();

    $metrics = [
      [
        'icon' => 'users',
        'title' => 'People Reached',
        'metric' => '50,000+',
        'description' => 'Individuals directly impacted across Malawi'
      ],
      [
        'icon' => 'school',
        'title' => 'Schools Supported',
        'metric' => '32',
        'description' => 'Educational institutions receiving our assistance'
      ],
      [
        'icon' => 'medical',
        'title' => 'Medical Camps',
        'metric' => '85',
        'description' => 'Health outreach programs conducted'
      ],
      [
        'icon' => 'water',
        'title' => 'Water Projects',
        'metric' => '120',
        'description' => 'Clean water access points installed'
      ],
      [
        'icon' => 'training',
        'title' => 'Training Sessions',
        'metric' => '450+',
        'description' => 'Skill development workshops conducted'
      ],
      [
        'icon' => 'women',
        'title' => 'Women Empowered',
        'metric' => '15,000+',
        'description' => 'Female participants in our programs'
      ],
      [
        'icon' => 'agriculture',
        'title' => 'Farmers Trained',
        'metric' => '2,500+',
        'description' => 'In sustainable agricultural practices'
      ],
      [
        'icon' => 'volunteers',
        'title' => 'Volunteers',
        'metric' => '1,200+',
        'description' => 'Active community volunteers'
      ]
    ]; /*ImpactMetric::where('is_published', true)
      ->orderBy('sort_order')
      ->get();*/

    return view('pages.home', [
      'slides' => $this->getSliderData(),
      'approaches' => $this->getApproachData(),
      'metrics' => $metrics,
      'programs' => $programs
    ]);
  }

  private function getSliderData(): array
  {
    return [
      [
        'image' => 'images/slide2.jpg',
        'title' => 'Empowering Communities',
        'description' => 'Building stronger communities through citizen engagement and advocacy',
      ],
      [
        'image' => 'images/slide1.jpg',
        'title' => 'Promoting Governance',
        'description' => 'Fostering transparency and accountability in governance processes',
      ],
      [
        'image' => 'images/slide3.jpg',
        'title' => 'Citizen Participation',
        'description' => 'Facilitating active citizen participation in development initiatives',
      ],
    ];
  }

  private function getApproachData(): array
  {
    return [
      [
        'title' => 'Bwalo la Nzika Approach',
        'description' => 'A mobilization tool aimed at facilitating policy dialogues between citizens and decision makers/duty bearers.',
        'icon' => 'chat-bubble-left-right',
      ],
      [
        'title' => 'Advocacy & Campaign Approach',
        'description' => 'Influencing change on matters affecting the vulnerable and marginalized groups.',
        'icon' => 'megaphone',
      ],
      [
        'title' => 'Research Approach',
        'description' => 'Collection of data and information to inform advocacy interventions.',
        'icon' => 'chart-bar',
      ],
    ];
  }
}
