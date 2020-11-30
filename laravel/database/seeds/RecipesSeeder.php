<?php

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipesSeeder extends Seeder
{
    private $recipeLists = [
        [
            'water' => '300',
            'temperature' => '100',
            'time' => '200',
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
            $recipe->recipe = json_encode($item);
            $recipe->save();
        }
    }
}
