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
        if (!Schema::hasColumn('users', 'membership_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('membership_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'membership_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('membership_date');
            });
        }
    }
};
