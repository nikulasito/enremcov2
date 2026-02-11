<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    protected $fillable = [
        'user_id',
        'application_no',
        'full_name',
        'member_key',
        'address',
        'loan_type',
        'loan_amount',
        'comaker1_user_id',
        'comaker1_name',
        'comaker1_position',
        'comaker2_user_id',
        'comaker2_name',
        'comaker2_position',
        'status',
        'reviewed_by',
        'reviewed_at',
        'remarks',
    ];
}
