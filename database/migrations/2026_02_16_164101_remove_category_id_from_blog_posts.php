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
        Schema::table('blog_posts', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['category_id']);

            // Then drop the column
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // Re-add the column
            $table->unsignedBigInteger('category_id')->nullable()->after('author_id');

            // Re-add the foreign key
            $table->foreign('category_id')->references('id')->on('blog_categories')->onDelete('set null');
        });
    }
};
