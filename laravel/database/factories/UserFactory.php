<?php

namespace Database\Factories;

use App\User;
use App\Models\Firm;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firms = [];
        $locations = [];
        if (rand() % 4 > 0) {
            $firms = Firm::pluck('id')->toArray();
            if (rand() % 4 > 0) {
                $locations = Location::pluck('id')->toArray();
            }
        }

        return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => bcrypt('password'),
        'remember_token' => Str::random(10),
        'firm_id' =>  $this->faker->randomElement($firms),
        'location_id' =>  $this->faker->randomElement($locations),
    ];
    }
}
