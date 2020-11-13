<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;

class PermissionUserService
{
    public static function getUserPermissions($user)
    {
        $roles = $user->getRoleNames();
        $rolesId = Role::select("id")->whereIn("name", $roles)->get();
        $permissions = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menu_id')
            ->select('menus.*')
            ->whereIn('menu_role.role_id', $rolesId)
            ->groupBy('menus.id')
            ->orderBy('menus.sequence', 'asc')->get();
        return $permissions;
    }
}
