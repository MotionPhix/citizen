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
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug')->unique();
      $table->text('description');
      $table->longText('content');
      $table->date('start_date');
      $table->string('funded_by');
      $table->date('end_date')->nullable();
      $table->json('key_achievements')->nullable();
      $table->enum('status', ['current', 'completed', 'upcoming']);
      $table->integer('people_reached')->default(0);
      $table->decimal('budget', 15, 2)->nullable();
      $table->json('meta_data')->nullable();
      $table->boolean('is_featured')->default(false);
      $table->integer('order')->default(0);
      $table->timestamps();
      $table->softDeletes();

      $table->fullText(['title', 'description', 'content']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('projects');
  }
};
