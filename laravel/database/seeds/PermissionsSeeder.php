<?php

use Illuminate\Database\Seeder;
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
        $this->insertLink('admin', 'Users', '/users');
        $this->insertLink('admin', 'Roles', '/roles');
        $this->insertLink('admin', 'Devices', '/devices');
        $this->insertLink('admin', 'Repairs', '/repairs');
        $this->insertLink('admin', 'OTA', '/otas');
        $this->insertLink('admin', 'Recipes', '/recipes');
        $this->insertLink('admin', 'Firmware', '/firmware');
        $this->insertLink('firm owner', 'Users', '/users');
        $this->insertLink('firm owner', 'Roles', '/roles');
        $this->insertLink('firm owner', 'Devices', '/devices');
        $this->insertLink('firm owner', 'Repairs', '/repairs');
        $this->insertLink('firm owner', 'OTA', '/otas');
        $this->insertLink('firm owner', 'Recipes', '/recipes');
        $this->insertLink('firm owner', 'Firmware', '/firmware');
        $this->insertLink('location owner', 'Users', '/users');
        $this->insertLink('location owner', 'Roles', '/roles');
        $this->insertLink('location owner', 'Devices', '/devices');
        $this->insertLink('location owner', 'Repairs', '/repairs');
        $this->insertLink('location owner', 'OTA', '/otas');
        $this->insertLink('location owner', 'Recipes', '/recipes');
        $this->insertLink('location owner', 'Firmware', '/firmware');
        $this->joinAllByTransaction();
    }
}
