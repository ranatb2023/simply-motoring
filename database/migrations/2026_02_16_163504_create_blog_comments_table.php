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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();

            // Post Reference
            $table->unsignedBigInteger('blog_post_id');
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');

            // Commenter Information
            $table->unsignedBigInteger('user_id')->nullable(); // Null if guest comment
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Guest Commenter Info (if user_id is null)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_website')->nullable();

            // Comment Content
            $table->text('content');

            // Nested Comments (Replies)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('blog_comments')->onDelete('cascade');

            // Moderation
            $table->enum('status', ['pending', 'approved', 'spam', 'trash'])->default('pending');
            $table->timestamp('approved_at')->nullable();

            // Engagement
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);

            // Spam Protection
            $table->string('ip_address', 45)->nullable(); // IPv4 and IPv6 support
            $table->string('user_agent')->nullable();
            $table->boolean('is_flagged')->default(false);

            // SEO - User Generated Content
            $table->boolean('is_indexed')->default(true); // Whether to include in search

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('blog_post_id');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('status');
            $table->index(['blog_post_id', 'status']); // Composite index
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
