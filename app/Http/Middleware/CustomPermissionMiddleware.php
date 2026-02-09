<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CustomPermissionMiddleware
{
    public function handle($request, Closure $next)
    {

        $user = Auth::user();

        if (! $user) {
            throw UnauthorizedException::notLoggedIn();
        }

        $routeName = $request->route()->getName();

        /*
        |--------------------------------------------------------------------------
        | Your custom "__" logic
        |--------------------------------------------------------------------------
        | example:
        | roles.index__update
        | -> roles.index
        */
        $permission = explode('__', $routeName)[0];

        if (! $user->can($permission)) {
            throw UnauthorizedException::forPermissions([$permission]);
        }

        return $next($request);
    }
}
