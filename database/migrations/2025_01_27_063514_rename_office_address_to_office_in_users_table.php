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
        if (Schema::hasColumn('users', 'office_address') && !Schema::hasColumn('users', 'office')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('office_address', 'office');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'office') && !Schema::hasColumn('users', 'office_address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('office', 'office_address');
            });
        }
    }
};
