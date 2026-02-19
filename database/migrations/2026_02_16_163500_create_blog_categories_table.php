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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // SEO Fields
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title', 60)->nullable();
            $table->string('og_description', 160)->nullable();
            $table->string('og_image')->nullable();

            // Hierarchy Support (for nested categories)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('blog_categories')->onDelete('set null');

            // Display Settings
            $table->string('color', 7)->default('#3B82F6'); // Hex color for category badge



            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('slug');
            $table->index('parent_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
