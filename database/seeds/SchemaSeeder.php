<?php

use App\Models\Schema;
use Illuminate\Database\Seeder;

class SchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skema = Schema::create([
            'title' => 'TKJ CLUSTER 1',
            'description' => 'hehehe cluster 1',
            'code' => 'awoekawkeokaweo',
            'status' => 'active'
        ]);

        for ($i=1; $i < 4; $i++)
        {
            $units = $skema->competency_units()->create([
                'code' => "00{$i}",
                'title' => "Unit{$i}"
            ]);
            
            for ($j=1; $j < 3; $j++)
            {
                $elements = $units->work_elements()->create([
                    'title' => "Element {$i}.{$j}"
                ]);

                $elements->job_criterias()->create([
                    'title' => "Criteria {$i}.{$j}.1"
                ]);
            }
        }
    }
}
