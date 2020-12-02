<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class UserPermissionService
{
    public static function getUserPermissions($user)
    {
        $roles = $user->getRoleNames();
        $rolesId = Role::select("id")->whereIn("name", $roles)->get();
        $permissions = Permission::join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('permissions.*')
            ->whereIn('role_has_permissions.role_id', $rolesId)
            ->groupBy('permissions.id')
            ->orderBy('permissions.sequence', 'asc')->get();
        return $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function getAllPermission()
    {
        $permissions = Permission::orderBy('sequence', 'asc')->groupBy('name')->get();
        return $permissions;
    }
}
