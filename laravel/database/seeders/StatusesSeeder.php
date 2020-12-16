<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    private $statusLists = [
        [
            'value' => '1',
            'class' => 'success',
            'type' => 'device',
            'name' => 'Enable', //啟用
        ],
        [
            'value' => '2',
            'class' => 'secondary',
            'type' => 'device',
            'name' => 'Disable', //未啟用
        ],
        [
            'value' => '3',
            'class' => 'danger',
            'type' => 'device',
            'name' => 'Fault', //壞掉
        ],
        [
            'value' => '4',
            'class' => 'secondary',
            'type' => 'ota',
            'name' => 'Updating', //更新中
        ],
        [
            'value' => '5',
            'class' => 'success',
            'type' => 'ota',
            'name' => 'Success', //更新成功
        ],
        [
            'value' => '6',
            'class' => 'danger',
            'type' => 'ota',
            'name' => 'Fail', //更新失敗
        ],
        [
            'value' => '7',
            'class' => 'success',
            'type' => 'working',
            'name' => 'Work', //使用中
        ],
        [
            'value' => '8',
            'class' => 'warning',
            'type' => 'working',
            'name' => 'Repair', //維修
        ],
        [
            'value' => '9',
            'class' => 'secondary',
            'type' => 'working',
            'name' => 'Idle', //閒置、停用
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert($this->statusLists);
    }
}
