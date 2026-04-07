<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'credit_reviewed_by')) {
                $table->foreignId('credit_reviewed_by')->nullable()->after('status')->constrained('users');
            }

            if (!Schema::hasColumn('loan_applications', 'credit_reviewed_at')) {
                $table->timestamp('credit_reviewed_at')->nullable()->after('credit_reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (Schema::hasColumn('loan_applications', 'credit_reviewed_at')) {
                $table->dropColumn('credit_reviewed_at');
            }

            if (Schema::hasColumn('loan_applications', 'credit_reviewed_by')) {
                $table->dropConstrainedForeignId('credit_reviewed_by');
            }
        });
    }
};
