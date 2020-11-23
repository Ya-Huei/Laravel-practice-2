<?php

use Illuminate\Database\Seeder;
use App\Models\Device;

class DevicesSeeder extends Seeder
{
    private $deviceLists = [
        [
            'serial_no' => '10000001A',
            'status' => '1',
            'address' => 'Daan',
            'location_id' => '1',
            'firm_id' => '1',
        ],
        [
            'serial_no' => '10000002A',
            'status' => '1',
            'address' => 'Zhonghe',
            'location_id' => '2',
            'firm_id' => '1',
        ],
        [
            'serial_no' => '10000003A',
            'status' => '1',
            'address' => 'Daan',
            'location_id' => '1',
            'firm_id' => '2',
        ],
        [
            'serial_no' => '10000004A',
            'status' => '1',
            'address' => 'Zhonghe',
            'location_id' => '2',
            'firm_id' => '2',
        ],
        [
            'serial_no' => '10000005A',
            'status' => '1',
            'address' => 'Sichuan',
            'location_id' => '4',
            'firm_id' => '1',
        ],
        [
            'serial_no' => '10000006A',
            'status' => '1',
            'address' => 'Haerbin',
            'location_id' => '5',
            'firm_id' => '1',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->deviceLists as $item) {
            $device = new Device();
            $device->serial_no = $item['serial_no'];
            $device->status = $item['status'];
            $device->address = $item['address'];
            $device->location_id = $item['location_id'];
            $device->firm_id = $item['firm_id'];
            $device->save();
        }
    }
}
