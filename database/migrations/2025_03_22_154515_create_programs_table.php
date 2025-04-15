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
    Schema::create('programs', function (Blueprint $table) {
      $table->id();

      $table->string('title');
      $table->string('slug')->unique();
      $table->text('description');
      $table->string('icon')->nullable();
      $table->string('image')->nullable();
      $table->text('objectives')->nullable();
      $table->text('achievements')->nullable();
      $table->integer('sort_order')->default(0);
      $table->boolean('is_published')->default(true);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('programs');
  }
};
