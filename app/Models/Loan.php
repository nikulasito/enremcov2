<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'member_id',
        'type_of_loan',
        'date_of_loan',
        'interest_rate',
        'number_of_terms',
        'date_loan_approved',
        'monthly_payment'
    ];

    protected $table = 'loan_details';

    // If your column is `employee_ID` (note uppercase), set $casts or $fillable accordingly.
    // Relation: loans.employee_ID -> users.employee_id
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_ID', 'employee_id');
    }

    // If you have member relation too
    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id', 'loan_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(LoanPayment::class, 'loan_id', 'loan_id')
            ->latestOfMany('date_of_remittance');
    }

    public function loanPayments()
    {
        // adjust foreign/local keys if needed
        return $this->hasMany(\App\Models\LoanPayment::class, 'loan_id', 'loan_id');
    }
}