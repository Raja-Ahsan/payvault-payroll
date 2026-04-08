<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Legacy installs used misspelled pivot table names (icome_*).
     */
    public function up(): void
    {
        if (Schema::hasTable('icome_category_taxes') && ! Schema::hasTable('income_category_taxes')) {
            Schema::rename('icome_category_taxes', 'income_category_taxes');
        }

        if (Schema::hasTable('icome_category_deductions') && ! Schema::hasTable('income_category_deductions')) {
            Schema::rename('icome_category_deductions', 'income_category_deductions');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('income_category_taxes') && ! Schema::hasTable('icome_category_taxes')) {
            Schema::rename('income_category_taxes', 'icome_category_taxes');
        }

        if (Schema::hasTable('income_category_deductions') && ! Schema::hasTable('icome_category_deductions')) {
            Schema::rename('income_category_deductions', 'icome_category_deductions');
        }
    }
};
