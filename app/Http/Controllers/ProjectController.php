<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  public function index(): Response
  {
    $featuredProjects = Project::featured()
      ->with(['media', 'tags'])
      ->orderBy('order')
      ->take(6)
      ->get()
      ->map(function ($project) {
        $data = $project->toArray();
        $data['featured_image_url'] = $project->featured_image;
        $data['location'] = $project->meta_data['location'] ?? null;
        return $data;
      });

    $projects = Project::with(['media', 'tags'])
      ->orderBy('order')
      ->orderBy('start_date', 'desc')
      ->paginate(12);

    // Transform paginated projects
    $projects->getCollection()->transform(function ($project) {
      $data = $project->toArray();
      $data['featured_image_url'] = $project->featured_image;
      $data['location'] = $project->meta_data['location'] ?? null;
      return $data;
    });

    $allProjects = Project::all();

    $impactStats = cache()->remember('project_impact_stats', 3600, function () use ($allProjects) {
      return collect([
        [
          'id' => 1,
          'title' => 'Projects Completed',
          'value' => (string) $allProjects->where('status', 'completed')->count(),
          'description' => 'Successfully completed projects',
          'icon' => 'check-circle'
        ],
        [
          'id' => 2,
          'title' => 'People Impacted',
          'value' => number_format($allProjects->sum('people_reached') / 1000, 1) . 'K',
          'description' => 'Lives touched through our initiatives',
          'icon' => 'users'
        ],
        [
          'id' => 3,
          'title' => 'Active Projects',
          'value' => (string) $allProjects->where('status', 'current')->count(),
          'description' => 'Currently running projects',
          'icon' => 'activity'
        ],
        [
          'id' => 4,
          'title' => 'Total Investment',
          'value' => '$' . number_format($allProjects->sum('budget') / 1000000, 1) . 'M',
          'description' => 'USD invested in community development',
          'icon' => 'dollar-sign'
        ],
      ]);
    });

    return Inertia::render('projects/Index', compact('projects', 'featuredProjects', 'impactStats'));
  }

  public function show(Project $project): Response
  {
    $project->load(['media', 'tags']);

    // Get related projects based on tags and status
    $relatedProjects = Project::where('id', '!=', $project->id)
      ->withAnyTags($project->tags->pluck('name')->toArray())
      ->with(['media', 'tags'])
      ->take(3)
      ->get();

    // If no tag-based related projects, get recent projects
    if ($relatedProjects->count() < 3) {
      $additionalProjects = Project::where('id', '!=', $project->id)
        ->whereNotIn('id', $relatedProjects->pluck('id'))
        ->with(['media', 'tags'])
        ->latest('start_date')
        ->take(3 - $relatedProjects->count())
        ->get();

      $relatedProjects = $relatedProjects->concat($additionalProjects);
    }

    // Transform project data to include computed attributes
    $projectData = $project->toArray();
    $projectData['featured_image_url'] = $project->featured_image;
    $projectData['gallery_images'] = $project->gallery_images;
    $projectData['location'] = $project->meta_data['location'] ?? null;

    // Transform related projects
    $relatedProjectsData = $relatedProjects->map(function ($relatedProject) {
      $data = $relatedProject->toArray();
      $data['featured_image_url'] = $relatedProject->featured_image;
      $data['location'] = $relatedProject->meta_data['location'] ?? null;
      return $data;
    });

    return Inertia::render('projects/Show', [
      'project' => $projectData,
      'relatedProjects' => $relatedProjectsData
    ]);
  }
}
