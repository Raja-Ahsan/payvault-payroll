<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('created_by')->constrained('users');
            $table->string('fed_filing_status')->nullable();
            $table->integer('fed_allowances')->nullable();
            $table->string('pay_frequency')->nullable();
            $table->decimal('additional_fed_withholding', 10, 2)->default(0);
            $table->boolean('use_new_w4_2020')->default(false);
            $table->boolean('w4_step2_two_jobs')->default(false);
            $table->decimal('w4_step3_dependents', 10, 2)->default(0);
            $table->decimal('w4_step4a_other_income', 10, 2)->default(0);
            $table->decimal('w4_step4b_deductions', 10, 2)->default(0);
            $table->boolean('w2_statutory_employee')->default(false);
            $table->boolean('w2_retirement_plan')->default(false);
            $table->boolean('w2_advance_eic')->default(false);
            $table->boolean('tax_zero_federal_income')->default(false);
            $table->boolean('tax_zero_state_income')->default(false);
            $table->boolean('tax_zero_ss_med_employee')->default(false);
            $table->boolean('tax_zero_ss_med_employer')->default(false);
            $table->foreignId('withholding_state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->decimal('additional_state_withholding', 10, 2)->default(0);
            $table->string('state_filing_status')->nullable();
            $table->integer('state_personal_allowances')->default(0);
            $table->integer('state_dependent_allowances')->default(0);
            $table->boolean('include_in_direct_deposit')->default(false);
            $table->string('account_type')->nullable(); // checking / savings
            $table->string('bank_routing_number')->nullable();
            $table->string('account_number')->nullable();
            $table->string('vacation_sick_calculation_method')->default('per_check');
            $table->decimal('vacation_hours_earned_per_unit', 10, 2)->default(0);
            $table->decimal('max_vacation_hours_per_year', 10, 2)->nullable();
            $table->decimal('sick_hours_earned_per_unit', 10, 2)->default(0);
            $table->decimal('max_sick_hours_per_year', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_details');
    }
};
