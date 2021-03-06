<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Enums\RoleNames;
use App\Http\Requests\Users\UserStoreFormValidation;
use App\Http\Requests\Users\UserUpdateFormValidation;
use App\Http\Requests\Users\UserEditValidation;
use App\Http\Requests\Users\UserDestroyValidation;
use App\Http\Resources\UserCollection;
use App\Models\Location;
use App\Models\Firm;
use App\Services\RolesService;
use App\Services\LocationsService;
use App\Services\FirmsService;
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
        $data = User::with('roles:name', 'location', 'firm:id,name')
            ->whereNull('deleted_at')
            ->orderBy('id', 'asc')
            ->ofFirmId(auth()->user()->firm_id)
            ->ofLocationId(auth()->user()->location_id)
            ->get();

        $users = new UserCollection($data);
        return response()->json(compact('users', 'you'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $roles = RolesService::getRolesOptions();
        $locations = LocationsService::getLocationsOptions();
        $firms = FirmsService::getFirmsOptions();
        return response()->json(compact('roles', 'locations', 'firms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(UserStoreFormValidation $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->remember_token = Str::random(10);

        if (array_key_exists("country", $validatedData)) {
            $user->location_id = !empty($validatedData['country']) ? LocationsService::getLocationId($validatedData['country'], $validatedData['region'], $validatedData['city']) : null;
        } else {
            $user->location_id = auth()->user()->location_id;
        }

        if (array_key_exists("firm", $validatedData) && isset($validatedData['firm'])) {
            if ($validatedData['firm'] === "0") {
                $user->firm_id = null;
            } else {
                $user->firm_id = $validatedData['firm'];
            }
        } else {
            $user->firm_id = auth()->user()->firm_id;
        }
        
        $user->save();

        if (array_key_exists("roles", $validatedData)) {
            if (!empty($validatedData['roles'])) {
                if (($key = array_search(RoleNames::ADMIN, $validatedData['roles'])) !== false) {
                    unset($validatedData['roles'][$key]);
                }
                $user->assignRole($validatedData['roles']);
            }
        } else {
            $user->assignRole(auth()->user()->roles()->get());
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(UserEditValidation $request, User $user)
    {
        $user->menuroles = $user->getRoleNames();
        LocationsService::getLocationInfo($user, $user->location_id);
        $roles = RolesService::getRolesOptions();
        $locations = LocationsService::getLocationsOptions();
        $firms = FirmsService::getFirmsOptions();

        return response()->json(compact('user', 'roles', 'locations', 'firms'));
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
        $validatedData = $request->validated();
        if (isset($validatedData['password'])) {
            $user->password  = bcrypt($validatedData['password']);
        }

        if (array_key_exists("country", $validatedData)) {
            $user->location_id = !empty($validatedData['country']) ? LocationsService::getLocationId($validatedData['country'], $validatedData['region'], $validatedData['city']) : null;
        }

        if (array_key_exists("firm", $validatedData) && isset($validatedData['firm'])) {
            if ($validatedData['firm'] === "0") {
                $user->firm_id = null;
            } else {
                $user->firm_id = $validatedData['firm'];
            }
        }
        
        $user->save();
        $user->syncRoles($validatedData['roles']);

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDestroyValidation $request, User $user)
    {
        $user->roles()->detach();
        $user->delete();
        return response()->json(['status' => 'success']);
    }
}
