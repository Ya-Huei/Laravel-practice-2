<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\User;

class UsersSeeder extends Seeder
{
    private $userLists = [
        [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'role' => 'admin',
        ],
        [
            'name' => 'coco',
            'email' => 'coco@coco.com',
            'password' => 'password',
            'firm_id' => '1',
            'role' => 'firm owner',
        ],
        [
            'name' => 'coco taipei',
            'email' => 'cocotaipei@cocotaipei.com',
            'password' => 'password',
            'location_id' => '1',
            'firm_id' => '1',
            'role' => 'location owner',
        ],
        [
            'name' => 'coco taipei2',
            'email' => 'cocotaipei2@cocotaipei2.com',
            'password' => 'password',
            'location_id' => '1',
            'firm_id' => '1',
            'role' => 'location owner',
        ],
        [
            'name' => 'jiate',
            'email' => 'jiate@jiate.com',
            'password' => 'password',
            'firm_id' => '2',
            'role' => 'firm owner',
        ],
        [
            'name' => 'jiate taipei',
            'email' => 'jiatetaipei@jiatetaipei.com',
            'password' => 'password',
            'location_id' => '1',
            'firm_id' => '2',
            'role' => 'location owner',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->userLists as $item) {
            $user = new User();
            $user->name = $item['name'];
            $user->email = $item['email'];
            $user->password = bcrypt($item['password']);
            $user->remember_token = Str::random(10);
            if (isset($item['location_id'])) {
                $user->location_id = $item['location_id'];
            }
            if (isset($item['firm_id'])) {
                $user->firm_id = $item['firm_id'];
            }
            $user->save();
            $user->assignRole($item['role']);
        }
    }
}
