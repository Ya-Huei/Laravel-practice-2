<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipesSeeder extends Seeder
{
    private $recipeLists = [
        [
            'recipe' => '3,30,1,0,0,4,100,2,0,0',
            'firm_id' => '1',
        ],
        [
            'recipe' => '3,30,1,0,0,6,100,2,0,0',
            'firm_id' => '2',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->recipeLists as $item) {
            $recipe = new Recipe();
            $recipe->recipe = $item['recipe'];
            $recipe->firm_id = $item['firm_id'];
            $recipe->save();
        }
    }
}
