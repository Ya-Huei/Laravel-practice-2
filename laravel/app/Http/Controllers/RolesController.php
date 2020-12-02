<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Roles\RoleStoreFormValidation;
use App\Http\Requests\Roles\RoleUpdateFormValidation;
use App\Models\Menurole;
use App\Services\RolePermissionsService;
use App\Services\RolesService;
use App\Services\UserPermissionService;
use App\User;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::select('id', 'name', 'updated_at as updated', 'created_at as registered')->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $permissions = UserPermissionService::getAllPermission();
        return response()->json($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(RoleStoreFormValidation $request)
    {
        $role = new Role();
        $name = $request->input('name');
        $role->name = $name;
        $role->save();

        RolePermissionsService::insertRolePermissions($role->id, $request->input('permissions'));

        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     */
    public function edit(Role $role)
    {
        if ($role->name == "admin") {
            return response()->json(['status' => '403']);
        }
        $permissions = UserPermissionService::getAllPermission();
        $userPermissions = RolePermissionsService::getRolePermissions($role->id);

        return response()->json(array(
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $permissions,
            'user_permissions' => $userPermissions,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     */
    public function update(RoleUpdateFormValidation $request, Role $role)
    {
        RolePermissionsService::deleteRolePermissions($role->id);
        RolePermissionsService::insertRolePermissions($role->id, $request->input('permissions'));
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     */
    public function destroy(Role $role)
    {
        if ($role->name == "admin") {
            return response()->json(['status' => '403']);
        }

        $role->delete();
        return response()->json(['status' => 'success']);
    }
}
