<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  public function index()
  {
    $featuredProjects = Project::featured()
      ->with(['media', 'tags'])
      ->orderBy('order')
      ->take(3)
      ->get();

    $projects = Project::with(['media', 'tags'])
      ->orderBy('order')
      ->orderBy('start_date', 'desc')
      ->paginate(12);

    $impactStats = Project::pluck('meta_data')
      ->filter()
      ->map(function ($meta) {
        return collect($meta)->only(['number', 'suffix', 'label'])->toArray();
      })
      ->filter()
      ->values();

    return view('pages.projects.index', compact('projects', 'featuredProjects', 'impactStats'));
  }

  public function show(Project $project)
  {
    $project->load(['media', 'tags']);

    return view('pages.projects.show', compact('project'));
  }
}
