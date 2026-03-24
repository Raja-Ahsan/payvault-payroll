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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('pay_date')->nullable();
            $table->string('pay_period')->nullable();
            $table->decimal('regular_hours', 8, 2)->default(0);
            $table->decimal('vacation_hours', 8, 2)->default(0);
            $table->decimal('sick_hours', 8, 2)->default(0);
            $table->decimal('holidays_hours', 8, 2)->default(0);
            $table->decimal('personal_hours', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('gross_pay', 10, 2)->default(0);
            $table->decimal('k401_amount', 10, 2)->default(0);
            $table->decimal('fed_tax', 10, 2)->default(0);
            $table->decimal('state_tax', 10, 2)->default(0);
            $table->decimal('local_tax', 10, 2)->default(0);
            $table->decimal('social_security', 10, 2)->default(0);
            $table->decimal('medi_care', 10, 2)->default(0);
            $table->decimal('insurance_deduction', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
