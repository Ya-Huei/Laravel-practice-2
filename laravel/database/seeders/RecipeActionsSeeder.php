<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecipeAction;

class RecipeActionsSeeder extends Seeder
{
    private $actionLists = [
        [
            'value' => '',
            'action' => 'No Act',
        ],
        [
            'value' => '1',
            'action' => 'Water gate open',
        ],
        [
            'value' => '2',
            'action' => 'Water gate close',
        ],
        [
            'value' => '3',
            'action' => 'Mat gate open',
        ],
        [
            'value' => '4',
            'action' => 'Mat gate close',
        ],
        [
            'value' => '5',
            'action' => 'Drain gate open',
        ],
        [
            'value' => '6',
            'action' => 'Drain gate close',
        ],
        [
            'value' => '7',
            'action' => 'Stir open',
        ],
        [
            'value' => '8',
            'action' => 'Stir close',
        ],
        [
            'value' => '9',
            'action' => 'Heater open',
        ],
        [
            'value' => '10',
            'action' => 'Heater close',
        ],
        [
            'value' => '11',
            'action' => 'intermittent stir open', // 間歇性攪拌開啟
        ],
        [
            'value' => '12',
            'action' => 'intermittent stir close', // 間歇性攪拌關閉
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return vovalue
     */
    public function run()
    {
        RecipeAction::insert($this->actionLists);
    }
}
