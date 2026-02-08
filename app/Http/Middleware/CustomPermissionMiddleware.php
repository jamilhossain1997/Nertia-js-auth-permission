<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permissionsString)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message'=>'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $permissions = explode('__', $permissionsString);

        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'auth.unauthorized',
            'errors' => [
                'permission' => ['There is no permission to access this URL by given user.'],
            ],
        ], Response::HTTP_UNAUTHORIZED);
    }
}
