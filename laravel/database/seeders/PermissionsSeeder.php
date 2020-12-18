<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    private $permissionId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();

    public function join($roles, $permissionsId)
    {
        $roles = explode(',', $roles);
        foreach ($roles as $role) {
            $role = Role::where('name', '=', $role)->first();
            array_push($this->joinData, array('role_id' => $role->id, 'permission_id' => $permissionsId));
        }
    }
    
    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction()
    {
        DB::beginTransaction();
        foreach ($this->joinData as $data) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $data['role_id'],
                'permission_id' => $data['permission_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null)
    {
        DB::table('permissions')->insert([
            'slug' => 'link',
            'name' => $name,
            'icon' => $icon,
            'href' => $href,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertLink('admin,firm owner,location owner', 'Users', '/users', 'cil-user');
        $this->insertLink('admin,firm owner,location owner', 'Roles', '/roles', 'cil-lock-locked');
        $this->insertLink('admin,firm owner,location owner', 'Devices', '/devices', 'cil-devices');
        $this->insertLink('admin,firm owner,location owner', 'Repairs', '/repairs', 'cil-bug');
        $this->insertLink('admin,firm owner,location owner', 'OTA', '/otas', 'cil-cloud-download');
        $this->insertLink('admin,firm owner,location owner', 'Recipes', '/recipes', 'cil-book');
        $this->insertLink('admin,firm owner,location owner', 'Firmware', '/firmware', 'cil-code');
        $this->joinAllByTransaction();
    }
}
