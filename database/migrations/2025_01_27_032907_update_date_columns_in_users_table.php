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
            if (Schema::hasColumn('users', 'date_inactive')) {
                $table->timestamp('date_inactive')->nullable()->change();
            }
            if (Schema::hasColumn('users', 'date_reactive')) {
                $table->timestamp('date_reactive')->nullable()->change();
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
                $table->string('date_inactive')->nullable()->change();
            }
            if (Schema::hasColumn('users', 'date_reactive')) {
                $table->string('date_reactive')->nullable()->change();
            }
        });
    }
};
