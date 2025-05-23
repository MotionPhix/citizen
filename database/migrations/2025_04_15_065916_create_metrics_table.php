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
    Schema::create('metrics', function (Blueprint $table) {
      $table->id();
      $table->string('icon');
      $table->string('title');
      $table->string('metric');
      $table->text('description');
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
    Schema::dropIfExists('metrics');
  }
};
