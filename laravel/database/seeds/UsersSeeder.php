<?php

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
        ],
        [
            'name' => 'coco',
            'email' => 'coco@coco.com',
            'password' => 'password',
            'firm_id' => '1',
        ],
        [
            'name' => 'coco north',
            'email' => 'coconorth@coconorth.com',
            'password' => 'password',
            'location_id' => '1',
            'firm_id' => '1',
        ],
        [
            'name' => 'coco north2',
            'email' => 'coconorth2@coconorth2.com',
            'password' => 'password',
            'location_id' => '2',
            'firm_id' => '1',
        ],
        [
            'name' => 'jiate',
            'email' => 'jiate@jiate.com',
            'password' => 'password',
            'firm_id' => '2',
        ],
        [
            'name' => 'jiate north',
            'email' => 'jiatenorth@jiatenorth.com',
            'password' => 'password',
            'location_id' => '1',
            'firm_id' => '2',
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
            $user->assignRole('admin');
        }
    }
}
