<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    protected $table = 'loan_applications';
    protected $fillable = [
        'user_id',
        'application_no',
        'full_name',
        'member_key',
        'address',
        'loan_type',
        'loan_amount',
        'loan_purpose',
        'beneficiary_name',
        'school_name',
        'school_program',
        'school_year',
        'semester',
        'appliance_item',
        'appliance_brand_model',
        'appliance_store',
        'appliance_cash_price',
        'appliance_items',
        'appliance_total_amount',
        'appliance_downpayment',
        'appliance_warranty_months',
        'grocery_partner_store',
        'grocery_period_from',
        'grocery_period_to',
        'household_size',
        'comaker1_user_id',
        'comaker1_name',
        'comaker1_position',
        'comaker2_user_id',
        'comaker2_name',
        'comaker2_position',
        'status',
        'credit_reviewed_by',
        'credit_reviewed_at',
        'reviewed_by',
        'reviewed_at',
        'remarks',
        'approved_amount',
        'old_balance',
        'lpp',
        'interest',
        'handling_fee',
        'petty_cash_loan',
        'total_deduction',
        'total_net',
        'terms',
        'monthly_payment',
        'run_term',
        'first_installment_date',
        'installment_increased_to',
        'simple_annual_rate',
    ];

    protected $casts = [
        'credit_reviewed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
