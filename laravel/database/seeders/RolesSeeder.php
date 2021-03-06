<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    private $roleLists = [
        [
            'name' => 'admin',
        ],
        [
            'name' => 'firm owner',
        ],
        [
            'name' => 'location owner',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roleLists as $item) {
            $role = new Role();
            $role->name = $item['name'];
            $role->save();
        }
    }
}
