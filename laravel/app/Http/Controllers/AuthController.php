<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\UserPermissionService;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $response = [
            'default_page'  => $this->getDefaultPage(),
            'access_token' => $token,
            'user_name' => auth()->user()->name,
            'user_firm' => auth()->user()->firm_id,
            'user_location' => auth()->user()->location_id,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];

        return response()->json($response);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    private function getDefaultPage()
    {
        $user = auth()->user();
        $permissions = UserPermissionService::getUserPermissions($user);
        foreach ($permissions as $key => $value) {
            return $value['href'];
        }
    }
}
