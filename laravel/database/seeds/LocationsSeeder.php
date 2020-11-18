<?php

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsSeeder extends Seeder
{
    private $locationLists = [
        [
            'country' => '台灣',
            'region' => '北區',
            'city' => '台北市'
        ],
        [
            'country' => '台灣',
            'region' => '北區',
            'city' => '新北市'
        ],
        [
            'country' => '台灣',
            'region' => '中區',
            'city' => '台中市'
        ],
        [
            'country' => '大陸',
            'region' => '四川省',
            'city' => '重慶市'
        ],
        [
            'country' => '大陸',
            'region' => '黑龍江省',
            'city' => '哈爾濱市'
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
