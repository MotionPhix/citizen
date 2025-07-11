<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('impact_metrics', function (Blueprint $table) {
      $table->id();
      $table->string('icon');
      $table->string('title');
      $table->string('metric');
      $table->text('description');
      $table->boolean('is_published')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('impact_metrics');
  }
};
