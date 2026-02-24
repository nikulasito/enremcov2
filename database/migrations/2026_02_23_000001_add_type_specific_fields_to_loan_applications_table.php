<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'loan_purpose')) {
                $table->string('loan_purpose', 500)->nullable()->after('loan_amount');
            }

            if (!Schema::hasColumn('loan_applications', 'beneficiary_name')) {
                $table->string('beneficiary_name')->nullable()->after('loan_purpose');
            }
            if (!Schema::hasColumn('loan_applications', 'school_name')) {
                $table->string('school_name')->nullable()->after('beneficiary_name');
            }
            if (!Schema::hasColumn('loan_applications', 'school_program')) {
                $table->string('school_program')->nullable()->after('school_name');
            }
            if (!Schema::hasColumn('loan_applications', 'school_year')) {
                $table->string('school_year', 50)->nullable()->after('school_program');
            }
            if (!Schema::hasColumn('loan_applications', 'semester')) {
                $table->string('semester', 50)->nullable()->after('school_year');
            }

            if (!Schema::hasColumn('loan_applications', 'appliance_item')) {
                $table->string('appliance_item')->nullable()->after('semester');
            }
            if (!Schema::hasColumn('loan_applications', 'appliance_brand_model')) {
                $table->string('appliance_brand_model')->nullable()->after('appliance_item');
            }
            if (!Schema::hasColumn('loan_applications', 'appliance_store')) {
                $table->string('appliance_store')->nullable()->after('appliance_brand_model');
            }
            if (!Schema::hasColumn('loan_applications', 'appliance_cash_price')) {
                $table->decimal('appliance_cash_price', 12, 2)->nullable()->after('appliance_store');
            }
            if (!Schema::hasColumn('loan_applications', 'appliance_downpayment')) {
                $table->decimal('appliance_downpayment', 12, 2)->nullable()->after('appliance_cash_price');
            }
            if (!Schema::hasColumn('loan_applications', 'appliance_warranty_months')) {
                $table->unsignedInteger('appliance_warranty_months')->nullable()->after('appliance_downpayment');
            }

            if (!Schema::hasColumn('loan_applications', 'grocery_partner_store')) {
                $table->string('grocery_partner_store')->nullable()->after('appliance_warranty_months');
            }
            if (!Schema::hasColumn('loan_applications', 'grocery_period_from')) {
                $table->date('grocery_period_from')->nullable()->after('grocery_partner_store');
            }
            if (!Schema::hasColumn('loan_applications', 'grocery_period_to')) {
                $table->date('grocery_period_to')->nullable()->after('grocery_period_from');
            }
            if (!Schema::hasColumn('loan_applications', 'household_size')) {
                $table->unsignedInteger('household_size')->nullable()->after('grocery_period_to');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            foreach ([
                'household_size',
                'grocery_period_to',
                'grocery_period_from',
                'grocery_partner_store',
                'appliance_warranty_months',
                'appliance_downpayment',
                'appliance_cash_price',
                'appliance_store',
                'appliance_brand_model',
                'appliance_item',
                'semester',
                'school_year',
                'school_program',
                'school_name',
                'beneficiary_name',
                'loan_purpose',
            ] as $column) {
                if (Schema::hasColumn('loan_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

