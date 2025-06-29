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
    Schema::create('metric_histories', function (Blueprint $table) {
      $table->id();
      $table->foreignId('impact_metric_id')->constrained()->onDelete('cascade');
      $table->bigInteger('value');
      $table->timestamp('recorded_at');
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->index(['impact_metric_id', 'recorded_at']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('metric_histories');
  }
};
