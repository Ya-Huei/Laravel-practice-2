<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RolesService
{
    public static function getRolesOptions()
    {
        $roles = Role::select('id', 'name')->where("name", "!=", "admin")
        ->get();
        return $roles;
    }
}
