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
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_type_id')->constrained('income_types')->onDelete('cascade');
            $table->string('title');
            $table->string('abbreviation')->nullable();
            $table->string('w2_box_12_code')->nullable();
            $table->string('w2_box_14_abbreviation')->nullable();
            $table->boolean('reported_tips')->default(false);
            $table->boolean('omit_net_pay')->default(false);
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
        Schema::dropIfExists('income_categories');
    }
};
