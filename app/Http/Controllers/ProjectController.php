<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
  public function index()
  {
    $featuredProjects = Project::featured()
      ->with(['media', 'tags'])
      ->orderBy('order')
      ->take(6)
      ->get();

    $projects = Project::with(['media', 'tags'])
      ->orderBy('order')
      ->orderBy('start_date', 'desc')
      ->paginate(10);

    $allProjects = Project::all();

    $impactStats = cache()->remember('project_impact_stats', 3600, function () use ($allProjects) {
      return collect([
        [
          'id' => 1,
          'number' => $allProjects->where('status', 'completed')->count(),
          'label' => 'Projects Completed'
        ],
        [
          'id' => 2,
          'number' => $allProjects->sum('people_reached'),
          'suffix' => 'K',
          'label' => 'People Impacted'
        ],
        [
          'id' => 3,
          'number' => $allProjects->where('status', 'current')->count(),
          'label' => 'Active Projects'
        ],
        [
          'id' => 4,
          'number' => $allProjects->sum('budget') / 1000000,
          'suffix' => 'M',
          'label' => 'USD Invested'
        ],
      ]);
    });

    return view('pages.projects.index', compact('projects', 'featuredProjects', 'impactStats'));
  }

  public function show(Project $project)
  {
    $project->load(['media', 'tags']);

    return view('pages.projects.show', compact('project'));
  }
}
