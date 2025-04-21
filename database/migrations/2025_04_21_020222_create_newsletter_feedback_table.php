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
    Schema::create('newsletter_feedback', function (Blueprint $table) {
      $table->id();
      $table->foreignId('newsletter_issue_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subscriber_id')->constrained()->cascadeOnDelete();
      $table->unsignedTinyInteger('rating');
      $table->text('comment')->nullable();
      $table->timestamps();

      $table->unique(['newsletter_issue_id', 'subscriber_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('newsletter_feedback');
  }
};
