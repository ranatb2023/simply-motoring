<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Support manually-logged (e.g. phone) bookings:
 *  - `source` records where a booking came from (online / phone / walk-in).
 *  - customer_email becomes nullable since phone customers may not give one.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('source', 20)->default('online')->after('status');
            $table->string('customer_email', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->string('customer_email', 100)->nullable(false)->change();
        });
    }
};
