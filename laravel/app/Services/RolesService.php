<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RolesService
{
    public static function getAllRoles()
    {
        $roles = Role::select('id', 'name', 'updated_at as updated', 'created_at as registered')
        ->get();
        return $roles;
    }
}
