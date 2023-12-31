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
        DB::table('users')->insert([
            'name' => 'PETRYCHIO',
            'email' => 'petyamaiko@gmail.com',
            'role' => 'ADMIN',
            'password' => Hash::make('secret')
        ]);
        DB::table('users')->insert([
            'name' => 'JANE',
            'email' => 'vgeniya.nizhenets@gmail.com',
            'role' => 'ADMIN',
            'password' => Hash::make('secret')
        ]);
        DB::table('users')->insert([
            'name' => 'Test',
            'email' => 'test@test.test',
            'password' => Hash::make('secret')
        ]);
    }
}
