<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'home_address')) {
                $table->string('home_address')->nullable()->after('contact_no');
            }

            if (!Schema::hasColumn('users', 'section')) {
                $table->string('section')->nullable()->after('home_address');
            }

            if (!Schema::hasColumn('users', 'sg_level')) {
                $table->integer('sg_level')->nullable()->after('position');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['home_address', 'section', 'sg_level'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
