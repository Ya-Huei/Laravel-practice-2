<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecipeAction;

class RecipeActionsSeeder extends Seeder
{
    private $actionLists = [
        [
            'id' => '1',
            'action' => 'Water gate open',
        ],
        [
            'id' => '2',
            'action' => 'Water gate close',
        ],
        [
            'id' => '3',
            'action' => 'Mat gate open',
        ],
        [
            'id' => '4',
            'action' => 'Mat gate close',
        ],
        [
            'id' => '5',
            'action' => 'Drain gate open',
        ],
        [
            'id' => '6',
            'action' => 'Drain gate close',
        ],
        [
            'id' => '7',
            'action' => 'Stir open',
        ],
        [
            'id' => '8',
            'action' => 'Stir close',
        ],
        [
            'id' => '9',
            'action' => 'Heater open',
        ],
        [
            'id' => '10',
            'action' => 'Heater close',
        ],
        [
            'id' => '11',
            'action' => 'intermittent stir open', // 間歇性攪拌開啟
        ],
        [
            'id' => '12',
            'action' => 'intermittent stir close', // 間歇性攪拌關閉
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RecipeAction::insert($this->actionLists);
    }
}
