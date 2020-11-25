<?php

use Illuminate\Database\Seeder;
use App\Models\Firmware;

class FirmwareSeeder extends Seeder
{
    private $firmwareLists = [
        [
            'version' => '1.0.0'
        ],
        [
            'version' => '1.0.1'
        ],
        [
            'version' => '2.0.0'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->firmwareLists as $item) {
            $firmware = new Firmware();
            $firmware->version = $item['version'];
            $firmware->save();
        }
    }
}
