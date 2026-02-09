<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'address',
        'contact_no',
        'password',
        'position',
        'email',
        'office',
        'religion',
        'sex',
        'status',
        'marital_status',
        'annual_income',
        'beneficiaries',
        'shares',
        'savings',
        'birthdate',
        'photo',
        'education',
        'employee_ID',
        'username',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_approved' => 'datetime',
            'date_inactive' => 'datetime',
            'date_reactive' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically set the default status when creating a new user
        static::creating(function ($user) {
            if (is_null($user->status)) {
                $user->status = 'Awaiting Approval';
            }
        });
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function loans()
    {
        // Switched to LoanDetail as it contains the complete logic for loan_details table
        return $this->hasMany(LoanDetail::class, 'employee_ID', 'employee_id');
    }

    public function withdrawals()
    {
        // Fixed class name from Withdrawal to Withdraw
        return $this->hasMany(\App\Models\Withdraw::class, 'employees_id', 'id');
    }


        // Mutators for automatically converting attributes to uppercase
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = strtoupper($value);
    }

    public function setOfficeAttribute($value)
    {
        $this->attributes['office'] = strtoupper($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtoupper($value);
    }

    public function setReligionAttribute($value)
    {
        $this->attributes['religion'] = strtoupper($value);
    }

    public function setSexAttribute($value)
    {
        $this->attributes['sex'] = strtoupper($value);
    }

    public function setMaritalStatusAttribute($value)
    {
        $this->attributes['marital_status'] = strtoupper($value);
    }

    public function setBeneficiariesAttribute($value)
    {
        $this->attributes['beneficiaries'] = strtoupper($value);
    }

    public function setEducationAttribute($value)
    {
        $this->attributes['education'] = strtoupper($value);
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtoupper($value);
    }

}
