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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('employee_id')->nullable();
            $table->text('address')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('annual_salary', 10, 2)->nullable();
            $table->decimal('regular_hourly_rate', 8, 2)->nullable();
            $table->decimal('overtime_hourly_rate', 8, 2)->nullable();
            $table->integer('federal_allowances')->default(0);
            $table->integer('401_k_contrib_percent')->default(0);
            $table->decimal('insurance_deduction', 10, 2)->nullable();
            $table->decimal('other_deductions', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
