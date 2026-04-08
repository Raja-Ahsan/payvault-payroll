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
        Schema::create('state_tax_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_tax_information');
    }
};
