<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => "Teknik Komputer Jaringan",
            'description' => "Kelompok Keahlian Komputer dan Jaringan"
        ]);
        Category::create([
            'name' => "Keahlian Dasar",
            'description' => "Kelompok Keahlian dasar"
        ]);
        for ($i=0; $i < 200; $i++) { 
            Category::create([
                'name' => "example category {$i}",
                'description' => "example category description {$i}"
            ]);
        }
    }
}
