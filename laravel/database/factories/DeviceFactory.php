<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Firm;
use App\Models\Location;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

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
        $statuses = Status::where('type', 'device')->pluck('id')->toArray();

        return [
            'serial_no' => $this->faker->unique()->numerify('##########A'),
            'status_id' => $this->faker->randomElement($statuses),
            'address' => $this->faker->address,
            'location_id' =>  $this->faker->randomElement($locations),
            'firm_id' =>  $this->faker->randomElement($firms),
        ];
    }
}
