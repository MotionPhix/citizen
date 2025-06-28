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
    Schema::create('contact_submissions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email');
      $table->string('subject');
      $table->longText('message');
      $table->longText('response')->nullable();
      $table->string('status')->default('unread');
      $table->string('ip_address')->nullable();
      $table->string('user_agent')->nullable();
      $table->string('referrer')->nullable();
      $table->timestamp('submitted_at')->nullable();
      $table->float('spam_score')->default(0.0);
      $table->timestamp('responded_at')->nullable();
      $table->foreignId('responded_by')->nullable()->constrained('users', 'id');
      $table->json('metadata')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('contact_submissions');
  }
};
