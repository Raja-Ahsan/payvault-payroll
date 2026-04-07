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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_type_id')->constrained('company_types')->onDelete('cascade');
            $table->string('employee_identification_number');
            $table->string('trade_name')->nullable();
            $table->boolean('round_federal_tax')->default(0);
            $table->string('control_number')->nullable();
            $table->string('establishment_number')->nullable();
            $table->string('other_ein')->nullable();
            $table->string('state_id')->nullable();
            $table->string('state_unemp_account_number')->nullable();
            $table->decimal('state_unemp_tax_rate_q1', 5, 2)->nullable();
            $table->decimal('state_unemp_tax_rate_q2', 5, 2)->nullable();
            $table->decimal('state_unemp_tax_rate_q3', 5, 2)->nullable();
            $table->decimal('state_unemp_tax_rate_q4', 5, 2)->nullable();
            $table->decimal('state_unemp_wage_base', 10, 2)->nullable();
            $table->string('first_fiscal_month')->nullable();
            $table->boolean('round_state_income_tax')->default(false);
            $table->boolean('hide_ssn_on_paystub')->default(false);
            $table->boolean('print_state_id_on_paystub')->default(false);
            $table->decimal('sdi_employee_rate', 5, 2)->nullable();
            $table->decimal('sdi_employee_wage_base', 10, 2)->nullable();
            $table->decimal('sdi_employer_rate', 5, 2)->nullable();
            $table->decimal('sdi_employer_wage_base', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
