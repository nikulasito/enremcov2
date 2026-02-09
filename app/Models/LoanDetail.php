<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LoanPayment;

class LoanDetail extends Model
{
    use HasFactory;

    protected $table = 'loan_details';
    protected $primaryKey = 'loan_id';  // Loan ID as primary key
    public $incrementing = false;  // Prevent auto-increment
    protected $keyType = 'string';  // Ensure `loan_id` is treated as a string

    protected $fillable = [
        'loan_id',
        'employee_ID',
        'loan_type',
        'loan_amount',
        'interest_rate',
        'date_applied',
        'date_approved',
        'total_net',
        'terms',
        'monthly_payment',
        'first_payment',
        'last_payment',
        'remarks',
        'total_deduction',
        'old_balance',
        'lpp',
        'handling_fee',
        'petty_cash_loan',
        'co_maker_name',
        'co_maker_position',
        'co_maker2_name',
        'co_maker2_position',
        'interest'
    ];

    public function user()
    {
        // loan_details.employee_ID -> users.employee_ID
        return $this->belongsTo(User::class, 'employee_ID', 'employee_ID');
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id', 'loan_id');
    }

    public function getLatestOutstandingBalanceAttribute()
    {
        $latestPayment = $this->loanPayments()->latest('created_at')->first();
        return $latestPayment ? $latestPayment->outstanding_balance : $this->loan_amount;
    }

    public function getLatestPaymentDateAttribute()
    {
        return $this->loanPayments()->latest('created_at')->value('latest_payment') ?? 'N/A';
    }

    public function getTotalPaymentsAttribute()
    {
        return $this->loanPayments()->sum('total_payments');
    }

    public function latestPayment()
    {
        return $this->hasOne(LoanPayment::class, 'loan_id', 'loan_id')
            ->latestOfMany('date_of_remittance');
    }
}
