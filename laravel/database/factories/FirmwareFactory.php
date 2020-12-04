<?php

namespace Database\Factories;

use App\Models\Firmware;
use Illuminate\Database\Eloquent\Factories\Factory;

class FirmwareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Firmware::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'version' => $this->faker->randomDigit . '.' . $this->faker->randomDigit . '.' . $this->faker->randomDigit,
        ];
    }
}
