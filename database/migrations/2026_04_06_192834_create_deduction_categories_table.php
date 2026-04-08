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
        Schema::create('deduction_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_type_id')->constrained('income_types')->onDelete('cascade');
            $table->string('title');
            $table->string('abbreviation')->nullable();
            $table->string('calculation')->default('percentage');
            $table->string('paid_by')->default('employee');
            $table->decimal('quarterly_rate_q1', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q2', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q3', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q4', 10, 4)->nullable();
            $table->decimal('cutoff', 12, 2)->nullable();
            $table->boolean('use_w2_box_10')->default(false);
            $table->boolean('use_w2_box_12')->default(false);
            $table->string('w2_box_12_code')->nullable();
            $table->boolean('use_w2_box_14')->default(false);
            $table->boolean('inactive')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduction_categories');
    }
};
