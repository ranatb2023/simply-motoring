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
        if (Schema::hasColumn('staff_service', 'staff_id')) {
            Schema::table('staff_service', function (Blueprint $table) {
                $table->renameColumn('staff_id', 'user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('staff_service', 'user_id')) {
            Schema::table('staff_service', function (Blueprint $table) {
                $table->renameColumn('user_id', 'staff_id');
            });
        }
    }
};
