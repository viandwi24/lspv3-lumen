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
        for ($i=0; $i < 30; $i++) { 
            Category::create([
                'name' => "Example Category {$i}",
                'description' => "{$i} This is a description of example category."
            ]);
        }
    }
}
