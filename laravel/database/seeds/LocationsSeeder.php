<?php

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsSeeder extends Seeder
{
    private $locationLists = [
        [
            'country' => 'Taiwan',
            'region' => 'North',
            'city' => 'Taipei'
        ],
        [
            'country' => 'Taiwan',
            'region' => 'North',
            'city' => 'NewTaipeiCity'
        ],
        [
            'country' => 'Taiwan',
            'region' => 'West',
            'city' => 'Taichung'
        ],
        [
            'country' => 'China',
            'region' => 'Sichuan',
            'city' => 'Chongqing'
        ],
        [
            'country' => 'China',
            'region' => 'Heilongjiang',
            'city' => 'Haerbin'
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->locationLists as $item) {
            $location = new Location();
            $location->country = $item['country'];
            $location->region = $item['region'];
            $location->city = $item['city'];
            $location->save();
        }
    }
}
