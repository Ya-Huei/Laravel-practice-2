<?php

namespace App\Services;

use App\Models\Menurole;

class MenuRoleService
{
    public static function insertRolePermissions($roleId, $permissions)
    {
        foreach ($permissions as $itemId) {
            $menuRole = new Menurole();
            $menuRole->role_id = $roleId;
            $menuRole->menu_id = $itemId;
            $menuRole->save();
        }
    }

    public static function deleteRolePermissions($roleId)
    {
        Menurole::where('role_id', $roleId)->delete();
    }

    public static function getRolePermissions($roleId)
    {
        $permissionDetail = Menurole::select('menu_id')->where('role_id', $roleId)->get();
        $permissions = array();

        foreach ($permissionDetail as $key => $value) {
            array_push($permissions, $value['menu_id']);
        }
        
        return $permissions;
    }
}
