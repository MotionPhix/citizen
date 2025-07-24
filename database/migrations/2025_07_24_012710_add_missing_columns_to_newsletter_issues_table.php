<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('newsletter_issues', function (Blueprint $table) {
      $table->timestamp('scheduled_at')->nullable()->after('published_at');
      $table->timestamp('sent_at')->nullable()->after('scheduled_at');
      $table->integer('issue_number')->nullable()->after('id');
      $table->string('template')->default('default')->after('status');
      $table->json('metadata')->nullable()->after('template');
      $table->foreignId('created_by')->nullable()->constrained('users')->after('metadata');
      $table->foreignId('published_by')->nullable()->constrained('users')->after('created_by');
      $table->integer('subscriber_count')->nullable()->after('published_by');
      $table->decimal('open_rate', 5, 2)->nullable()->after('subscriber_count');
      $table->decimal('click_rate', 5, 2)->nullable()->after('open_rate');

      // Update status enum to include all values the model expects
      $table->dropColumn('status');
    });

    // Add the status column with the correct enum values
    Schema::table('newsletter_issues', function (Blueprint $table) {
      $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft')->after('featured_image');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('newsletter_issues', function (Blueprint $table) {
      $table->dropColumn([
        'scheduled_at',
        'sent_at',
        'issue_number',
        'template',
        'metadata',
        'created_by',
        'published_by',
        'subscriber_count',
        'open_rate',
        'click_rate',
      ]);

      $table->dropColumn('status');
    });

    // Restore original status enum
    Schema::table('newsletter_issues', function (Blueprint $table) {
      $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft')->after('featured_image');
    });
  }
};
