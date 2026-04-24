<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $id = DB::table('state_reporting_method_options as m')
            ->join('state_reporting_tax_types as t', 't.id', '=', 'm.state_reporting_tax_type_id')
            ->where('t.state_code', 'TX')
            ->where('t.slug', 'tx_ui')
            ->where('m.slug', 'tx_icesa')
            ->value('m.id');

        if ($id) {
            DB::table('state_reporting_method_options')->where('id', $id)->update([
                'meta' => json_encode([
                    'icesa_intro' => 'Enter transmitter information and the output file path for the magnetic/electronic wage file. The file name must include the .ICE extension to be valid for Texas Workforce Commission (TWC) filing.',
                    'output_path_placeholder' => 'C:\\Users\\YourName\\Desktop\\TWCWAGES.ICE',
                ], JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $id = DB::table('state_reporting_method_options as m')
            ->join('state_reporting_tax_types as t', 't.id', '=', 'm.state_reporting_tax_type_id')
            ->where('t.state_code', 'TX')
            ->where('t.slug', 'tx_ui')
            ->where('m.slug', 'tx_icesa')
            ->value('m.id');

        if ($id) {
            DB::table('state_reporting_method_options')->where('id', $id)->update([
                'meta' => null,
                'updated_at' => now(),
            ]);
        }
    }
};
