<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreFormValidation;
use App\Http\Requests\UserUpdateFormValidation;
use App\User;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $you = auth()->user()->id;
        $users = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('group_concat(roles.name SEPARATOR ", ") as roles'), 'users.updated_at as updated', 'users.created_at as registered')
            ->whereNull('deleted_at')
            ->groupBy('users.name')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json(compact('users', 'you'));
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
    public function store(UserStoreFormValidation $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->remember_token = Str::random(10);
        $roles = $request->input('roles');
        $user->save();

        foreach ($roles as $role) {
            $user->assignRole($role);
        }
        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if ($user->name == "admin") {
            return response()->json(['status' => '403']);
        }
        $user->menuroles = $user->getRoleNames();
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateFormValidation $request, User $user)
    {
        $password = $request->input('password');
        if (isset($password) && $password != "") {
            $user->password  = bcrypt($password);
        }

        $user->save();
        
        $roles = $request->input('roles');
        $user->syncRoles($roles);

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->name == "admin") {
            return response()->json(['status' => '403']);
        }

        $user->roles()->detach();
        $user->delete();
        return response()->json(['status' => 'success']);
    }
}
