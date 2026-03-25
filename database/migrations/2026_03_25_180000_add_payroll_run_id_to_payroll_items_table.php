<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_items', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_items', 'payroll_run_id')) {
                return;
            }

            $table->foreignId('payroll_run_id')
                ->nullable()
                ->after('id')
                ->constrained('payroll_runs')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_items', function (Blueprint $table) {
            if (! Schema::hasColumn('payroll_items', 'payroll_run_id')) {
                return;
            }

            $table->dropForeign(['payroll_run_id']);
            $table->dropColumn('payroll_run_id');
        });
    }
};
