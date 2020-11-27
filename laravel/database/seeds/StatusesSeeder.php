<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    private $statusLists = [
        [
            'class' => 'success',
            'type' => 'device',
            'name' => 'Enable', //啟用
        ],
        [
            'class' => '',
            'type' => 'device',
            'name' => 'Disable', //未啟用
        ],
        [
            'class' => 'warning',
            'type' => 'device',
            'name' => 'Repair', //維修
        ],
        [
            'class' => 'danger',
            'type' => 'device',
            'name' => 'Fault', //壞掉
        ],
        [
            'class' => 'secondary',
            'type' => 'device',
            'name' => 'Idle', //閒置、停用
        ],
        [
            'class' => 'secondary',
            'type' => 'ota',
            'name' => 'Updating', //更新中
        ],
        [
            'class' => 'success',
            'type' => 'ota',
            'name' => 'Success', //更新成功
        ],
        [
            'class' => 'danger',
            'type' => 'ota',
            'name' => 'Fail', //更新失敗
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statusLists as $item) {
            $status = new Status();
            $status->class = $item['class'];
            $status->type = $item['type'];
            $status->name = $item['name'];
            $status->save();
        }
    }
}
