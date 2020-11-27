<?php

use Illuminate\Database\Seeder;
use App\Models\OtaRecord;

class OtaRecordsSeeder extends Seeder
{
    private $otaLists = [
        [
            'device_id' => '1',
            'type' => '1',
            'type_id' => '1',
            'status_id' => '6'
        ],
        [
            'device_id' => '2',
            'type' => '1',
            'type_id' => '1',
            'status_id' => '6'
        ],
        [
            'device_id' => '1',
            'type' => '1',
            'type_id' => '2',
            'status_id' => '7'
        ],
        [
            'device_id' => '1',
            'type' => '2',
            'type_id' => '1',
            'status_id' => '8'
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
            $otaRecords->type_id = $item['type_id'];
            $otaRecords->status_id = $item['status_id'];
            $otaRecords->save();
        }
    }
}
