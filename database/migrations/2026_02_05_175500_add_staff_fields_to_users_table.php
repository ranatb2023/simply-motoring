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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 50)->nullable();
            $table->text('info')->nullable();
            $table->integer('limit_hours')->default(0);
            $table->mediumInteger('schedule_id')->default(0);
            $table->string('timezone', 100)->default('UTC');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'info', 'limit_hours', 'schedule_id', 'timezone']);
        });
    }
};
