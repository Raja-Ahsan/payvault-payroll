<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_checks', function (Blueprint $table) {
            $table->json('check_preview')->nullable()->after('calculation_warnings');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_checks', function (Blueprint $table) {
            $table->dropColumn('check_preview');
        });
    }
};
