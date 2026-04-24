<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('state_reporting_tax_types', function (Blueprint $table) {
            $table->id();
            $table->string('state_code', 2);
            $table->string('slug', 64);
            $table->string('label');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['state_code', 'slug'], 'sr_tax_types_state_slug_uq');
            $table->index(['state_code', 'is_active'], 'sr_tax_types_state_active_idx');
        });

        Schema::create('state_reporting_method_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_reporting_tax_type_id');
            $table->string('slug', 64);
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('link_text')->nullable();
            $table->string('flow_kind', 32)->default('generic');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['state_reporting_tax_type_id', 'slug'], 'sr_mopts_tax_slug_uq');
            $table->index(['is_active', 'sort_order'], 'sr_mopts_active_sort_idx');
            $table->foreign('state_reporting_tax_type_id', 'sr_mopts_tax_type_fk')
                ->references('id')
                ->on('state_reporting_tax_types')
                ->cascadeOnDelete();
        });

        $now = now();

        $caDe9c = DB::table('state_reporting_tax_types')->insertGetId([
            'state_code' => 'CA',
            'slug' => 'ca_de_9c',
            'label' => 'CA DE 9C (Withholding Income Tax & Unemployment Insurance)',
            'sort_order' => 1,
            'is_active' => true,
            'meta' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $caDe9 = DB::table('state_reporting_tax_types')->insertGetId([
            'state_code' => 'CA',
            'slug' => 'ca_de_9',
            'label' => 'CA DE 9',
            'sort_order' => 2,
            'is_active' => true,
            'meta' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $caUi = DB::table('state_reporting_tax_types')->insertGetId([
            'state_code' => 'CA',
            'slug' => 'ca_ui',
            'label' => 'California Unemployment Insurance',
            'sort_order' => 3,
            'is_active' => true,
            'meta' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $txUi = DB::table('state_reporting_tax_types')->insertGetId([
            'state_code' => 'TX',
            'slug' => 'tx_ui',
            'label' => 'Texas Unemployment Insurance',
            'sort_order' => 1,
            'is_active' => true,
            'meta' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('state_reporting_method_options')->insert([
            [
                'state_reporting_tax_type_id' => $caDe9c,
                'slug' => 'ca_state_form_de9c',
                'label' => 'California State Form ( DE 9C )',
                'description' => 'With this option the application will generate the state form on blank paper.',
                'link_text' => 'WE print the state form on blank paper, YOU send it to the state',
                'flow_kind' => 'printed_form',
                'sort_order' => 1,
                'is_active' => true,
                'meta' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'state_reporting_tax_type_id' => $caDe9,
                'slug' => 'ca_state_form_de9',
                'label' => 'California State Form ( DE 9 )',
                'description' => 'With this option the application will generate the state form on blank paper.',
                'link_text' => 'WE print the state form on blank paper, YOU send it to the state',
                'flow_kind' => 'printed_form',
                'sort_order' => 1,
                'is_active' => true,
                'meta' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'state_reporting_tax_type_id' => $caUi,
                'slug' => 'ca_ui_generic_report',
                'label' => 'California Unemployment Insurance Generic Report',
                'description' => 'With this option the application will generate a report that lists the total wages, total taxable wages, and the list of employees along with their wages. You can then use this report to manually prepare the Quarterly Unemployment Insurance Report either on paper or by filing online through the state website.',
                'link_text' => 'WE provide the data, YOU prepare the form',
                'flow_kind' => 'generic',
                'sort_order' => 1,
                'is_active' => true,
                'meta' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'state_reporting_tax_type_id' => $txUi,
                'slug' => 'tx_icesa',
                'label' => 'Paperless Filing (ICESA)',
                'description' => 'Generate a magnetic/electronic (.ICE) wage file for submission to the Texas Workforce Commission (TWC), e.g. via TWC QuickFile.',
                'link_text' => null,
                'flow_kind' => 'icesa',
                'sort_order' => 1,
                'is_active' => true,
                'meta' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'state_reporting_tax_type_id' => $txUi,
                'slug' => 'tx_ui_generic_report',
                'label' => 'Texas Unemployment Insurance Generic Report',
                'description' => 'This report can be used to manually report on paper or through the state\'s website.',
                'link_text' => null,
                'flow_kind' => 'generic',
                'sort_order' => 2,
                'is_active' => true,
                'meta' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('state_reporting_method_options');
        Schema::dropIfExists('state_reporting_tax_types');
    }
};
