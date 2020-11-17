<?php

namespace App\Http\Middleware;

use App\Services\UserPermissionService;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (empty($user)) {
            return response()->json(['message' => 'Unauthenticated. Admin role required'], 401);
        }

        $path = $this->getPath($request->path());
        $permissions = UserPermissionService::getUserPermissions($user);

        if ($this->checkPermission($permissions, $path)) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthenticated. Admin role required'], 401);
    }

    private function getPath($url)
    {
        $paths = explode("/", $url);
        if (isset($paths[1])) {
            return $paths[1];
        }
    }

    private function checkPermission($permissions, $path)
    {
        foreach ($permissions as $key => $value) {
            if (substr($value->href, 1) == $path) {
                return true;
            }
        }
    }
}
