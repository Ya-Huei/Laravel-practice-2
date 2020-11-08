<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Menurole;
use App\Models\RoleHierarchy;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = DB::table('roles')
        // ->leftJoin('role_hierarchy', 'roles.id', '=', 'role_hierarchy.role_id')
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
            'name' => 'required|min:1|max:128'
        ]);
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();
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
        return response()->json( array(
            'id' => $role->id,
            'name' => $role->name
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
            'name' => 'required|min:1|max:128'
        ]);
        $role = Role::where('id', '=', $id)->first();
        $role->name = $request->input('name');
        $role->save();
        //$request->session()->flash('message', 'Successfully updated role');
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
        // $roleHierarchy = RoleHierarchy::where('role_id', '=', $id)->first();
        $menuRole = Menurole::where('role_name', '=', $role->name)->first();
        if(!empty($menuRole)){
            return response()->json( ['status' => 'rejected'] );
        }else{
            $role->delete();
            // $roleHierarchy->delete();
            return response()->json( ['status' => 'success'] );
        }
    }
}
