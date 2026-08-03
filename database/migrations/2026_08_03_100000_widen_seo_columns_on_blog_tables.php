<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SEO meta fields were hard-limited at the DB level (meta_title 60,
 * meta_description 160, etc.), which blocked publishing when authors
 * exceeded Google's recommended lengths. Those lengths are now enforced
 * as soft warnings in the app, so widen the columns to allow longer text.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->string('og_title', 255)->nullable()->change();
            $table->string('og_description', 500)->nullable()->change();
            $table->string('twitter_title', 255)->nullable()->change();
            $table->string('twitter_description', 500)->nullable()->change();
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->string('og_title', 255)->nullable()->change();
            $table->string('og_description', 500)->nullable()->change();
        });

        Schema::table('blog_tags', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('meta_title', 60)->nullable()->change();
            $table->string('meta_description', 160)->nullable()->change();
            $table->string('og_title', 60)->nullable()->change();
            $table->string('og_description', 160)->nullable()->change();
            $table->string('twitter_title', 60)->nullable()->change();
            $table->string('twitter_description', 160)->nullable()->change();
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('meta_title', 60)->nullable()->change();
            $table->string('meta_description', 160)->nullable()->change();
            $table->string('og_title', 60)->nullable()->change();
            $table->string('og_description', 160)->nullable()->change();
        });

        Schema::table('blog_tags', function (Blueprint $table) {
            $table->string('meta_title', 60)->nullable()->change();
            $table->string('meta_description', 160)->nullable()->change();
        });
    }
};
