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
        Schema::create('tax_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_type_id')->constrained('income_types')->onDelete('cascade');
            $table->string('title');
            $table->string('abbreviation')->nullable();
            $table->string('calculation')->default('percentage');
            $table->decimal('quarterly_rate_q1', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q2', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q3', 10, 4)->nullable();
            $table->decimal('quarterly_rate_q4', 10, 4)->nullable();
            $table->decimal('wage_base', 12, 2)->nullable();
            $table->decimal('max_amount_per_check', 12, 2)->nullable();
            $table->string('paid_by')->default('employee');
            $table->string('w2_box_12_code')->nullable();
            $table->string('w2_box_14_abbreviation')->nullable();
            $table->boolean('use_box_19')->default(false);
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
        Schema::dropIfExists('tax_categories');
    }
};
