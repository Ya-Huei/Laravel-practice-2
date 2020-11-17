<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Permissions;

class UserPermissionService
{
    public static function getUserPermissions($user)
    {
        $roles = $user->getRoleNames();
        $rolesId = Role::select("id")->whereIn("name", $roles)->get();
        $permissions = Permissions::join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('permissions.*')
            ->whereIn('role_has_permissions.role_id', $rolesId)
            ->groupBy('permissions.id')
            ->orderBy('permissions.sequence', 'asc')->get();
        return $permissions;
    }
}
