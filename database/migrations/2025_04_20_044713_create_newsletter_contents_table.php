<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('newsletter_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_issue_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['story', 'event', 'update', 'announcement']);
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('category')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['newsletter_issue_id', 'type']);
            $table->index(['type', 'order']);
            $table->index(['type', 'category']);
            $table->index(['is_featured', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_contents');
    }
};
