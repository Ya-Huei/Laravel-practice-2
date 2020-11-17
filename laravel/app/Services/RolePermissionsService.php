<?php

namespace App\Services;

use App\Models\RoleHasPermissions;

class RolePermissionsService
{
    public static function insertRolePermissions($roleId, $permissions)
    {
        foreach ($permissions as $itemId) {
            $menuRole = new RoleHasPermissions();
            $menuRole->role_id = $roleId;
            $menuRole->permission_id = $itemId;
            $menuRole->save();
        }
    }

    public static function deleteRolePermissions($roleId)
    {
        RoleHasPermissions::where('role_id', $roleId)->delete();
    }

    public static function getRolePermissions($roleId)
    {
        $permissionDetail = RoleHasPermissions::select('permission_id')->where('role_id', $roleId)->get();
        $permissions = array();

        foreach ($permissionDetail as $key => $value) {
            array_push($permissions, $value['permission_id']);
        }
        
        return $permissions;
    }
}
