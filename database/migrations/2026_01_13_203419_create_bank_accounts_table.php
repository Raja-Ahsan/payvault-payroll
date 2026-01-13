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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('accountable'); // Polymorphic: can belong to Company or Employee
            $table->string('account_type'); // checking, savings
            $table->string('bank_name');
            $table->string('account_holder_name');
            $table->string('routing_number'); // Encrypted
            $table->string('account_number'); // Encrypted
            $table->enum('verification_status', ['pending', 'verified', 'failed'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_primary')->default(false);
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
        Schema::dropIfExists('bank_accounts');
    }
};
