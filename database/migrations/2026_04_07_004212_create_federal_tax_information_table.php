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
        Schema::create('federal_tax_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_type_id')->constrained('company_types')->onDelete('cascade');
            $table->string('employer_identification_number');
            $table->string('trade_name')->nullable();
            $table->boolean('round_federal_tax')->default(0);
            $table->string('control_number')->nullable();
            $table->string('establishment_number')->nullable();
            $table->string('other_ein')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('federal_tax_information');
    }
};
