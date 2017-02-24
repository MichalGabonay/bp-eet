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
            'ico' => '123',
            'dic' => '456',
            'logo' => '',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('companies')->insert([
            'name' => 'Firma B',
            'cert_id' => null,
            'ico' => '987',
            'dic' => '654',
            'logo' => '',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
