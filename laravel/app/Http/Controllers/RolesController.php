<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Menurole;
use App\User;
use App\Services\MenuRoleService;
use App\Http\Requests\RoleStoreFormValidation;
use App\Http\Requests\RoleUpdateFormValidation;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = DB::table('roles')
        ->select('id', 'name', 'updated_at as updated', 'created_at as registered')
        ->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return response()->json(['status' => 'success']);
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

        MenuRoleService::insertRolePermissions($role->id, $request->input('permissions'));

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $role = Role::where('id', '=', $id)->first();
        return response()->json(array('name' => $role->name));
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

        $permissions = MenuRoleService::getRolePermissions($role->id);

        return response()->json(array(
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $permissions
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
        MenuRoleService::deleteRolePermissions($role->id);
        MenuRoleService::insertRolePermissions($role->id, $request->input('permissions'));
        
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
