<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Firma A',
            'cert_id' => null,
            'ico' => '12345678',
            'dic' => 'CZ00000019',
            'logo' => '',
            'address' => 'Široká 123/23, 612 00 Brno',
            'phone' => '7734562',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('companies')->insert([
            'name' => 'Firma B',
            'cert_id' => null,
            'ico' => '87654321',
            'dic' => 'CZ05360722',
            'logo' => '',
            'address' => 'Ulica 420/23, 058 01 Poprad',
            'phone' => '730998602',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
