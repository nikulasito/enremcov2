<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $table = 'savings';
    protected $primaryKey = 'savings_id'; // ✅ Add this line

    protected $fillable = [
        'employees_id',
        'name',
        'date',
        'date_remittance',
        'amount',
        'office',
        'date_created',
        'covered_month',
        'covered_year',
        'remittance_no',
    ];

    public $timestamps = false; // If you don't need Laravel's created_at and updated_at
}
