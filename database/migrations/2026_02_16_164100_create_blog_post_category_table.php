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
        Schema::create('blog_post_category', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('blog_post_id');
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');

            $table->unsignedBigInteger('blog_category_id');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');

            // Optional: Track if this is the primary category
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['blog_post_id', 'blog_category_id']);

            // Indexes for performance
            $table->index('blog_post_id');
            $table->index('blog_category_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_category');
    }
};
