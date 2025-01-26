<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('categories')->insert([
        'title' => 'İçecekler',
      ]);
      DB::table('categories')->insert([
        'title' => 'Sıcaklar'
      ]);
      DB::table('categories')->insert([
        'title' => 'Tatlılar'
      ]);
    }
}
