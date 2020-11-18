<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RolesService
{
    public static function getAllRoles()
    {
        $roles = DB::table('roles')
        ->select('id', 'name', 'updated_at as updated', 'created_at as registered')
        ->get();
        return $roles;
    }
}
