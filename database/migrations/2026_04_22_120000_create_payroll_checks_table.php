<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('check_number')->default(1);
            $table->string('pay_frequency', 32);
            $table->date('pay_date');
            $table->date('period_begin_date')->nullable();
            $table->date('period_end_date')->nullable();
            $table->decimal('gross_total', 12, 2)->default(0);
            $table->decimal('ss_taxable_wages', 12, 2)->default(0);
            $table->decimal('futa_taxable_wages', 12, 2)->default(0);
            $table->decimal('suta_taxable_wages', 12, 2)->default(0);
            $table->json('income_breakdown')->nullable();
            $table->decimal('employee_federal_income_tax', 12, 2)->default(0);
            $table->decimal('employee_social_security', 12, 2)->default(0);
            $table->decimal('employee_medicare', 12, 2)->default(0);
            $table->decimal('employee_state_income_tax', 12, 2)->default(0);
            $table->decimal('employee_local_income_tax', 12, 2)->default(0);
            $table->decimal('employee_state_disability', 12, 2)->default(0);
            $table->decimal('employer_social_security', 12, 2)->default(0);
            $table->decimal('employer_medicare', 12, 2)->default(0);
            $table->decimal('employer_federal_unemployment', 12, 2)->default(0);
            $table->decimal('employer_state_unemployment', 12, 2)->default(0);
            $table->decimal('employer_state_disability', 12, 2)->default(0);
            $table->decimal('deduction_401k', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('employee_taxes_total', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);
            $table->text('memo')->nullable();
            $table->json('calculation_warnings')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'pay_date']);
            $table->index(['company_id', 'pay_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_checks');
    }
};
