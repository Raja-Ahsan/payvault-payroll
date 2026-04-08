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
