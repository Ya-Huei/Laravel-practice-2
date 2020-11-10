<?php

namespace App\Services;

use App\Models\Menurole;

class MenuRoleService{

    public static function insertRolePermissions($role_name, $permissions){
        foreach($permissions as $item_id){
            $menuRole = new Menurole();
            $menuRole->role_name = $role_name;
            $menuRole->menus_id = $item_id;
            $menuRole->save();
        }
    }

    public static function deleteRolePermissions($role_name){
        Menurole::where('role_name', $role_name)->delete();
    }

    public static function getPermissions($role_name){
        $permissionDetail = Menurole::select('menus_id')->where('role_name', '=', $role_name)->get();
        $permissions = array();

        foreach ($permissionDetail as $key => $value) {
            array_push($permissions, $value['menus_id']);
        }
        
        return $permissions;
    }
}