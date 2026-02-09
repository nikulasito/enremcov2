<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $table = 'loan_payments';

    protected $fillable = [
        'loan_id',
        'total_payments_count',
        'total_payments',
        'outstanding_balance',
        'latest_remittance',
        'date_of_remittance',
        'remittance_no',
        'date_covered_month',
        'date_covered_year'
    ];

    public function loan()
    {
        return $this->belongsTo(LoanDetail::class, 'loan_id', 'loan_id');
    }
}
