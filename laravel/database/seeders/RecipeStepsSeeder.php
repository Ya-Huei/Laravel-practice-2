<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecipeStep;

class RecipeStepsSeeder extends Seeder
{
    private $stepLists = [
        [
            'id' => '0',
            'step' => 'Finish', // 結束
        ],
        [
            'id' => '1',
            'step' => 'Instant', // 立即-不停留
        ],
        [
            'id' => '2',
            'step' => 'Continue for a period of time', // 持續一段時間
        ],
        [
            'id' => '3',
            'step' => 'Water flood', // 注水XX公升
            'unit' => 'L'
        ],
        [
            'id' => '4',
            'step' => 'Heating', // 加熱至XX度C
            'unit' => '℃'
        ],
        [
            'id' => '5',
            'step' => 'Cool down', // 降溫至XX度C
            'unit' => '℃'
        ],
        [
            'id' => '6',
            'step' => 'Washing', // XX公升水洗
            'unit' => 'L'
        ],
        [
            'id' => '7',
            'step' => 'Washing time', // 水洗XX秒
            'unit' => 'sec'
        ],
        [
            'id' => '9',
            'step' => 'thermostable mode', // 恆溫模式
            'unit' => 'sec'
        ],
        [
            'id' => '11',
            'step' => 'Heating time', // 火力XX秒
            'unit' => 'sec'
        ],
        [
            'id' => '100',
            'step' => 'set intermittent stir', // 設定間歇性攪拌參數
        ],
        [
            'id' => '109',
            'step' => 'thermostable temperature', // 設定恆溫溫度
            'unit' => '℃'
        ],
        [
            'id' => '111',
            'step' => 'Heating percent', // 設定火力XX%
            'unit' => '%'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->stepLists as $item) {
            $step = new RecipeStep();
            $step->id = $item['id'];
            $step->step = $item['step'];
            if (isset($item['unit'])) {
                $step->unit = $item['unit'];
            }
            $step->save();
        }
    }
}
