<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdrawals';
    protected $primaryKey = 'withdrawals_id'; // adjust if your PK is different
    public $timestamps = false;               // toggle if you actually use created_at/updated_at

    protected $fillable = [
        'employees_id',       // ✅ IMPORTANT
        'name',
        'office',
        'date_of_withdrawal',
        'amount_withdrawn',
        'reference_no',
        'covered_month',
        'covered_year',
        'remarks',
        'date_created',
        'date_updated',
    ];

    protected $casts = [
        'employees_id'     => 'integer',
        'amount_withdrawn' => 'float',
        'covered_month'    => 'integer',
        'covered_year'     => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employees_id', 'id');
    }
}
