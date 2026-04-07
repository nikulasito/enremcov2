<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'run_term')) {
                $table->string('run_term', 50)->nullable()->after('monthly_payment');
            }
            if (!Schema::hasColumn('loan_applications', 'first_installment_date')) {
                $table->date('first_installment_date')->nullable()->after('run_term');
            }
            if (!Schema::hasColumn('loan_applications', 'installment_increased_to')) {
                $table->decimal('installment_increased_to', 12, 2)->nullable()->after('first_installment_date');
            }
            if (!Schema::hasColumn('loan_applications', 'simple_annual_rate')) {
                $table->string('simple_annual_rate', 50)->nullable()->after('installment_increased_to');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            foreach ([
                'simple_annual_rate',
                'installment_increased_to',
                'first_installment_date',
                'run_term',
            ] as $column) {
                if (Schema::hasColumn('loan_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
