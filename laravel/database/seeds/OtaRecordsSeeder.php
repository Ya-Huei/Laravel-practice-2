<?php

use Illuminate\Database\Seeder;
use App\Models\OtaRecord;

class OtaRecordsSeeder extends Seeder
{
    private $otaLists = [
        [
            'device_id' => '1',
            'type' => '1',
            'version' => '1'
        ],
        [
            'device_id' => '2',
            'type' => '1',
            'version' => '1'
        ],
        [
            'device_id' => '1',
            'type' => '1',
            'version' => '2'
        ],
        [
            'device_id' => '1',
            'type' => '2',
            'version' => '1'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->otaLists as $item) {
            $otaRecords = new OtaRecord();
            $otaRecords->device_id = $item['device_id'];
            $otaRecords->type = $item['type'];
            $otaRecords->version = $item['version'];
            $otaRecords->save();
        }
    }
}
