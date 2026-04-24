<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_tax_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('tax_category_id')->constrained('tax_categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'tax_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_tax_category');
    }
};
