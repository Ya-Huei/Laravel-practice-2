<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupsSeeder extends Seeder
{
    private $groupLists = [
        [
            'value' => '1',
            'name' => 'COCO_RC',
            'firm_id' => '1'
        ],
        [
            'value' => '2',
            'name' => 'COCO_FC',
            'firm_id' => '1'
        ],
        [
            'value' => '3',
            'name' => 'COCO_VC',
            'firm_id' => '1'
        ],
        [
            'value' => '4',
            'name' => 'JIATE_RC',
            'firm_id' => '2'
        ],
        [
            'value' => '5',
            'name' => 'JIATE_FC',
            'firm_id' => '2'
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->groupLists as $item) {
            $group = new Group();
            $group->value = $item['value'];
            $group->name = $item['name'];
            $group->firm_id = $item['firm_id'];
            $group->save();
        }
    }
}
