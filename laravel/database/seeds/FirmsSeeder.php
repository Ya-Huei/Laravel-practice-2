<?php

use Illuminate\Database\Seeder;
use App\Models\Firm;

class FirmsSeeder extends Seeder
{
    private $firmLists = [
        [
            'name' => 'CoCo'
        ],
        [
            'name' => 'Jiate'
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->firmLists as $item) {
            $firm = new Firm();
            $firm->name = $item['name'];
            $firm->save();
        }
    }
}
