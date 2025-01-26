<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('users')->insert([
        'name' => 'Yusuf Emir Tatlı',
        'email' => 'yusufemirtatli96@gmail.com',
        'password' => Hash::make('26062001'),
        'created_at' => '2024-10-20',
      ]);
    }
}
