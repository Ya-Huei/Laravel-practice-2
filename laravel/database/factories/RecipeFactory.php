<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipe' => json_encode([
                'water' => $this->faker->numberBetween(100, 900), 
                'temperature' => $this->faker->numberBetween(100, 900),
                'time' => $this->faker->numberBetween(100, 900)]),
        ];
    }
}
