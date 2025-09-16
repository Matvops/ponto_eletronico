<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'user1',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('Senha123'),
                'role' => 'USER',
                'email_verified_at' => Carbon::now(),
                'token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
                'confirmation_code' => null
            ],
            [
                'username' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => bcrypt('Senha123'),
                'role' => 'ADMIN',
                'email_verified_at' => Carbon::now(),
                'token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
                'confirmation_code' => null
            ]
        ]);
    }
}
