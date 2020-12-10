<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RepairRecord;

class RepairRecordsSeeder extends Seeder
{
    private $repairLists = [
        [
            'device_id' => '1',
            'reason' => 'Bumb',
            'worker' => 'My Father',
            'status_id' => '3',
        ],
        [
            'device_id' => '2',
            'reason' => 'Bumb',
            'worker' => 'My Mother',
            'status_id' => '3',
        ],
        [
            'device_id' => '3',
            'reason' => 'Bumb',
            'worker' => 'My Brother',
            'status_id' => '3',
        ],
    ];

    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->repairLists as $item) {
            $repair = new RepairRecord();
            $repair->device_id = $item['device_id'];
            $repair->reason = $item['reason'];
            $repair->worker = $item['worker'];
            $repair->status_id = $item['status_id'];
            $repair->save();
        }
    }
}
