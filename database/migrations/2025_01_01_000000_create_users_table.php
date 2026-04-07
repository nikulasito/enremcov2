<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('photo')->nullable();
                $table->string('username')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('address')->nullable();
                $table->string('contact_no')->nullable();
                $table->string('position')->nullable();
                $table->string('office')->nullable();
                $table->string('religion')->nullable();
                $table->string('sex')->nullable();
                $table->string('status')->nullable();
                $table->string('marital_status')->nullable();
                $table->decimal('annual_income', 15, 2)->nullable();
                $table->text('beneficiaries')->nullable();
                $table->decimal('shares', 12, 2)->nullable();
                $table->decimal('savings', 12, 2)->nullable();
                $table->date('birthdate')->nullable();
                $table->string('education')->nullable();
                $table->string('employee_ID')->nullable()->unique();
                $table->boolean('is_admin')->default(false);
                $table->string('role')->nullable();
                $table->date('membership_date')->nullable();
                $table->timestamp('date_approved')->nullable();
                $table->timestamp('date_inactive')->nullable();
                $table->timestamp('date_reactive')->nullable();
                $table->string('email_verification_token')->nullable();
                $table->text('approve_notes')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
