<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    private $statusLists = [
        [
            'class' => 'success',
            'name' => 'Enable', //啟用
        ],
        [
            'class' => '',
            'name' => 'Disable', //未啟用
        ],
        [
            'class' => 'warning',
            'name' => 'Repair', //維修
        ],
        [
            'class' => 'danger',
            'name' => 'Fault', //壞掉
        ],
        [
            'class' => 'secondary',
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
        foreach ($this->statusLists as $item) {
            $status = new Status();
            $status->class = $item['class'];
            $status->name = $item['name'];
            $status->save();
        }
    }
}
