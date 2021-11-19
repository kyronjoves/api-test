<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $data = [
            [
                'id' => $faker->Uuid(),
                'role' => 'admin',
                'name' => "Admin",
                'user_name' => 'admin_role',
                'password' => Hash::make('password'),
                'email' => 'sample_admin@gmail.com'
            ],
            [
                'id' => $faker->Uuid(),
                'role' => 'client',
                'name' => "Client",
                'user_name' => 'client_role',
                'password' => Hash::make('password'),
                'email' => 'sample_client@gmail.com'
            ],
        ];
        DB::table('users')->insert($data);
    }
}
