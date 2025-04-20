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
    Schema::create('events', function (Blueprint $table) {
      $table->id();
      $table->foreignId('newsletter_issue_id')->constrained()->cascadeOnDelete();
      $table->string('title');
      $table->text('description');
      $table->string('location');
      $table->timestamp('start_date');
      $table->timestamp('end_date')->nullable();
      $table->string('registration_url')->nullable();
      $table->string('image')->nullable();
      $table->integer('capacity')->nullable();
      $table->integer('order')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('events');
  }
};
