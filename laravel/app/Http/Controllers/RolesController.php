<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Menurole;
use App\User;
use App\Services\MenuRoleService;
use Illuminate\Support\Facades\Log;

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
        return response()->json( $roles );
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return response()->json( ['status' => 'success'] );  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:128',
            'permissions' => 'required'
        ]);
        $role = new Role();
        $name = $request->input('name');
        $role->name = $name;
        $role->save();

        MenuRoleService::insertRolePermissions($name, $request->input('permissions'));

        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $role = Role::where('id', '=', $id)->first();
        return response()->json( array('name' => $role->name) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $role = Role::where('id', '=', $id)->first();
        $permissions = MenuRoleService::getPermissions($role->name);

        return response()->json( array(
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $permissions
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required'
        ]);
        $role = Role::where('id', '=', $id)->first();

        MenuRoleService::deleteRolePermissions($role->name);
        MenuRoleService::insertRolePermissions($role->name, $request->input('permissions'));
        
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id, Request $request)
    {
        $role = Role::where('id', '=', $id)->first();
        $users = User::role($role->name)->get();

        if(count($users) !== 0){
            return response()->json( ['status' => 'fail', 'message' => 'Please remove the role of the users who has this role.'] );
        }
        
        if($role){
            Menurole::where('role_name', '=', $role->name)->delete();
            $role->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
