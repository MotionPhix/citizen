<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterContent;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Only run if the old tables exist and the new table is empty
    if (!Schema::hasTable('newsletter_contents') || NewsletterContent::count() > 0) {
      return;
    }

    // Migrate Stories
    if (Schema::hasTable('stories')) {
      $stories = DB::table('stories')->get();
      foreach ($stories as $story) {
        NewsletterContent::create([
          'newsletter_issue_id' => $story->newsletter_issue_id,
          'type' => 'story',
          'title' => $story->title,
          'excerpt' => $story->excerpt,
          'content' => $story->content,
          'image' => $story->image,
          'url' => $story->url,
          'published_at' => $story->published_at,
          'order' => $story->order ?? 0,
          'created_at' => $story->created_at,
          'updated_at' => $story->updated_at,
        ]);
      }
    }

    // Migrate Events
    if (Schema::hasTable('events')) {
      $events = DB::table('events')->get();
      foreach ($events as $event) {
        NewsletterContent::create([
          'newsletter_issue_id' => $event->newsletter_issue_id,
          'type' => 'event',
          'title' => $event->title,
          'excerpt' => $event->description,
          'content' => $event->description,
          'image' => $event->image,
          'order' => $event->order ?? 0,
          'metadata' => json_encode([
            'location' => $event->location,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'registration_url' => $event->registration_url,
            'capacity' => $event->capacity,
          ]),
          'created_at' => $event->created_at,
          'updated_at' => $event->updated_at,
        ]);
      }
    }

    // Migrate Updates
    if (Schema::hasTable('updates')) {
      $updates = DB::table('updates')->get();
      foreach ($updates as $update) {
        NewsletterContent::create([
          'newsletter_issue_id' => $update->newsletter_issue_id,
          'type' => 'update',
          'title' => $update->title,
          'excerpt' => $update->excerpt,
          'content' => $update->content,
          'image' => $update->image,
          'url' => $update->url,
          'category' => $update->category,
          'order' => $update->order ?? 0,
          'created_at' => $update->created_at,
          'updated_at' => $update->updated_at,
        ]);
      }
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // This migration is not reversible as it would require
    // recreating the original tables and splitting the data back
    throw new Exception('This migration is not reversible. Please restore from backup if needed.');
  }
};
