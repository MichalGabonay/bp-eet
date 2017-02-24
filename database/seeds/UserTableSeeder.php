<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Michal Gabonay',
            'username' => 'admin',
            'email' => 'test@test.com',
            'password' => bcrypt('Poklop12'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test 1',
            'username' => 'test1',
            'email' => 'test1@test.com',
            'password' => bcrypt('Poklop12'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test 2',
            'username' => 'test2',
            'email' => 'test2@test.com',
            'password' => bcrypt('Poklop12'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test 3',
            'username' => 'test3',
            'email' => 'test3@test.com',
            'password' => bcrypt('Poklop12'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

    }
}
