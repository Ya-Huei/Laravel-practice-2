<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreFormValidation;
use App\Http\Requests\UserUpdateFormValidation;
use App\User;
use App\Services\RolesService;
use App\Services\LocationsService;
use App\Services\FirmsService;

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
        $data = User::with('roles:name', 'location', 'firm:id,name')
            ->whereNull('deleted_at')
            ->orderBy('id', 'asc')
            ->get();

        $users = $this->formatUsers($data);
        return response()->json(compact('users', 'you'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $roles = RolesService::getAllRoles();
        $locations = LocationsService::getLocationsCategory();
        $firms = FirmsService::getAllFirmsName();
        return response()->json(compact('roles', 'locations', 'firms'));
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
        $roles = RolesService::getAllRoles();
        return response()->json(compact('user', 'roles'));
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

    private function formatUsers($data)
    {
        $users = [];
        foreach ($data as $item) {
            $user = [];
            $user['id'] = $item->id;
            $user['name'] = $item->name;
            $user['email'] = $item->email;
            $user['roles'] = isset($item->roles) ? $this->formatRoles($item->roles) : "";
            $user['region'] = isset($item->location) ? $this->formatRegion($item->location) : "";
            $user['firm'] = isset($item->firm->name) ? $item->firm->name : "";
            $user['updated'] = $item->updated_at->format('Y-m-d H:i:s');
            $user['registered'] = $item->created_at->format('Y-m-d H:i:s');
            array_push($users, $user);
        }
        return $users;
    }

    private function formatRoles($roles)
    {
        $rolesName = [];
        foreach ($roles as $role) {
            array_push($rolesName, $role->name);
        }
        return $rolesName;
    }
    
    private function formatRegion($location)
    {
        return $location->country . ',' . $location->region . ',' . $location->city;
    }
}
