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
    Schema::create('stories', function (Blueprint $table) {
      $table->id();
      $table->foreignId('newsletter_issue_id')->constrained()->cascadeOnDelete();
      $table->string('title');
      $table->text('excerpt');
      $table->longText('content');
      $table->string('image')->nullable();
      $table->string('url')->nullable();
      $table->timestamp('published_at')->nullable();
      $table->integer('order')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('stories');
  }
};
