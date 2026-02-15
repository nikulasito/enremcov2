<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'password' => ['nullable', 'string', 'confirmed', 'min:8'],
            'position' => ['nullable', 'string', 'max:255'],
            'office' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'in:Male,Female,Other'],
            'marital_status' => ['nullable', 'in:Single,Married,Divorced,Widowed'],
            'annual_income' => ['nullable', 'numeric', 'min:0'],
            'contact_no' => ['nullable', 'numeric'],
            'beneficiaries' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'education' => ['nullable', 'string', 'max:255'],
            'employee_ID' => ['nullable', 'string', 'max:255'],
            'shares' => ['nullable', 'numeric', 'min:0'],
            'savings' => ['nullable', 'numeric', 'min:0'],
            'username' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
        ];
    }
}
