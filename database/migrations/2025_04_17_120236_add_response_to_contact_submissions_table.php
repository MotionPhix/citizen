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
    Schema::table('contact_submissions', function (Blueprint $table) {
      $table->text('response')->nullable()->after('message');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('contact_submissions', function (Blueprint $table) {
      $table->dropColumn('response');
    });
  }
};
