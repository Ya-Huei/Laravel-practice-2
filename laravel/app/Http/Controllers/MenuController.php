<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserPermissionService;
use App\Models\Permission;
use App\MenuBuilder\RenderFromDatabaseData;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $roles = '';
            if ($user && !empty($user)) {
                $roles =  $user->getRoleNames();
            }
        } catch (Exception $e) {
            $roles = '';
        }

        $permissions = UserPermissionService::getUserPermissions($user);

        $rfd = new RenderFromDatabaseData;
        return response()->json($rfd->render($permissions));
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllMenu()
    {
        $permissions = Permission::select('permissions.*')->orderBy('permissions.sequence', 'asc')->get();
        return response()->json($permissions);
    }
}
