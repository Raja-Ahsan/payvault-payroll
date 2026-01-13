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
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->string('deduction_type'); // 401k, health_insurance, dental, vision, etc.
            $table->string('description')->nullable();
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('is_pre_tax')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_deductions');
    }
};
