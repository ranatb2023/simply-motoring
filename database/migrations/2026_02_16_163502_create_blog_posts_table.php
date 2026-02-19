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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();

            // Author Information
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');

            // Category
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('blog_categories')->onDelete('set null');

            // Basic Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Short summary for listings
            $table->longText('content');

            // Featured Image
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable(); // SEO: Alt text for image
            $table->string('featured_image_caption')->nullable();

            // SEO Fields
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable(); // For duplicate content management
            $table->string('focus_keyword')->nullable(); // Primary SEO keyword

            // Open Graph (Social Media)
            $table->string('og_title', 60)->nullable();
            $table->string('og_description', 160)->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('article');

            // Twitter Card
            $table->string('twitter_title', 60)->nullable();
            $table->string('twitter_description', 160)->nullable();
            $table->string('twitter_image')->nullable();

            // Schema.org Structured Data
            $table->json('schema_data')->nullable(); // Store Article schema markup

            // Publishing
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            // Reading Time & Engagement
            $table->integer('reading_time')->nullable(); // In minutes
            $table->integer('views_count')->default(0);
            $table->integer('shares_count')->default(0);

            // SEO Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->boolean('is_indexed')->default(true); // noindex/index
            $table->boolean('is_followed')->default(true); // nofollow/follow

            // Additional Settings
            $table->string('template')->default('default'); // For different post layouts
            $table->integer('order')->default(0); // For manual ordering

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance and SEO
            $table->index('slug');
            $table->index('author_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('published_at');
            $table->index('is_featured');
            $table->index('is_indexed');
            $table->index(['status', 'published_at']); // Composite index for queries
            $table->fullText(['title', 'content', 'excerpt']); // Full-text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
