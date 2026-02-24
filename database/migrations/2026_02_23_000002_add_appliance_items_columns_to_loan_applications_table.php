<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'appliance_items')) {
                $table->longText('appliance_items')->nullable()->after('appliance_cash_price');
            }

            if (!Schema::hasColumn('loan_applications', 'appliance_total_amount')) {
                $table->decimal('appliance_total_amount', 12, 2)->nullable()->after('appliance_items');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            foreach (['appliance_total_amount', 'appliance_items'] as $column) {
                if (Schema::hasColumn('loan_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

