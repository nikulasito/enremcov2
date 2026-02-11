<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('application_no')->unique()->nullable();

            $table->string('full_name');
            $table->string('member_key')->nullable(); // employee_ID or employees_id etc.
            $table->string('address')->nullable();

            $table->string('loan_type'); // regular|educational|appliance|grocery
            $table->decimal('loan_amount', 12, 2);

            $table->foreignId('comaker1_user_id')->constrained('users');
            $table->string('comaker1_name');
            $table->string('comaker1_position')->nullable();

            $table->foreignId('comaker2_user_id')->constrained('users');
            $table->string('comaker2_name');
            $table->string('comaker2_position')->nullable();

            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
