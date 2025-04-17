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
    Schema::create('newsletter_sends', function (Blueprint $table) {
      $table->id();
      $table->foreignId('newsletter_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subscriber_id')->constrained()->cascadeOnDelete();
      $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
      $table->text('error_message')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('newsletter_sends');
  }
};
