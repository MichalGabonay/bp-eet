<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
//        $this->call(CompaniesTableSeeder::class);
//        $this->call(UserCompanySeeder::class);
//        $this->call(UserCompanyRolesSeeder::class);
    }
}
