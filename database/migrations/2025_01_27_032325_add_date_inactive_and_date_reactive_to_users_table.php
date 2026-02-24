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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'date_inactive')) {
                $table->timestamp('date_inactive')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'date_reactive')) {
                $table->timestamp('date_reactive')->nullable()->after('date_inactive');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'date_inactive')) {
                $table->dropColumn('date_inactive');
            }
            if (Schema::hasColumn('users', 'date_reactive')) {
                $table->dropColumn('date_reactive');
            }
        });
    }
};
