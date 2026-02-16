<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'approved_amount')) {
                $table->decimal('approved_amount', 12, 2)->nullable()->after('loan_amount');
            }
            if (!Schema::hasColumn('loan_applications', 'old_balance')) {
                $table->decimal('old_balance', 12, 2)->nullable()->after('remarks');
            }
            if (!Schema::hasColumn('loan_applications', 'lpp')) {
                $table->decimal('lpp', 12, 2)->nullable()->after('old_balance');
            }
            if (!Schema::hasColumn('loan_applications', 'interest')) {
                $table->decimal('interest', 12, 2)->nullable()->after('lpp');
            }
            if (!Schema::hasColumn('loan_applications', 'handling_fee')) {
                $table->decimal('handling_fee', 12, 2)->nullable()->after('interest');
            }
            if (!Schema::hasColumn('loan_applications', 'petty_cash_loan')) {
                $table->decimal('petty_cash_loan', 12, 2)->nullable()->after('handling_fee');
            }
            if (!Schema::hasColumn('loan_applications', 'total_deduction')) {
                $table->decimal('total_deduction', 12, 2)->nullable()->after('petty_cash_loan');
            }
            if (!Schema::hasColumn('loan_applications', 'total_net')) {
                $table->decimal('total_net', 12, 2)->nullable()->after('total_deduction');
            }
            if (!Schema::hasColumn('loan_applications', 'terms')) {
                $table->unsignedInteger('terms')->nullable()->after('total_net');
            }
            if (!Schema::hasColumn('loan_applications', 'monthly_payment')) {
                $table->decimal('monthly_payment', 12, 2)->nullable()->after('terms');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            foreach ([
                'monthly_payment',
                'terms',
                'total_net',
                'total_deduction',
                'petty_cash_loan',
                'handling_fee',
                'interest',
                'lpp',
                'old_balance',
                'approved_amount',
            ] as $column) {
                if (Schema::hasColumn('loan_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

