<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('billing_label')->nullable()->comment('e.g. /Flat yearly Fee');
            $table->string('billing_cycle', 32)->default('yearly');
            $table->string('cta_label')->default('Start Today');
            $table->json('features')->nullable();
            $table->string('quickbooks_item_id')->nullable()->comment('QuickBooks product/service item id');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
