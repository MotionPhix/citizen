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
    Schema::table('contact_submissions', function (Blueprint $table) {
      $table->string('referrer')->nullable();
      $table->timestamp('submitted_at')->nullable();
      $table->float('spam_score')->default(0.0);
      $table->timestamp('responded_at')->nullable();
      $table->foreignId('responded_by')->constrained('users', 'id');
      $table->json('metadata')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('contact_submissions', function (Blueprint $table) {
      $table->dropColumn(['referrer', 'submitted_at', 'spam_score', 'metadata']);
    });
  }
};
