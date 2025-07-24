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
        Schema::table('comments', function (Blueprint $table) {
            // Make user_id nullable to allow anonymous comments
            $table->foreignId('user_id')->nullable()->change();

            // Add fields for anonymous commenters
            $table->string('author_name')->nullable()->after('user_id');
            $table->string('author_email')->nullable()->after('author_name');
            $table->string('author_website')->nullable()->after('author_email');
            $table->ipAddress('ip_address')->nullable()->after('author_website');
            $table->text('user_agent')->nullable()->after('ip_address');

            // Add moderation fields
            $table->enum('status', ['pending', 'approved', 'spam', 'trash'])->default('pending')->after('is_approved');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');

            // Add notification fields
            $table->boolean('notify_on_reply')->default(true)->after('approved_by');
            $table->string('reply_token')->nullable()->after('notify_on_reply');

            // Add spam detection fields
            $table->decimal('spam_score', 3, 2)->default(0)->after('reply_token');
            $table->json('spam_data')->nullable()->after('spam_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn([
                'author_name',
                'author_email',
                'author_website',
                'ip_address',
                'user_agent',
                'status',
                'approved_at',
                'approved_by',
                'notify_on_reply',
                'reply_token',
                'spam_score',
                'spam_data'
            ]);

            // Make user_id required again
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
