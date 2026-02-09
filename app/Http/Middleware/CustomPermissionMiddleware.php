<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Http\Response;


class CustomPermissionMiddleware
{
    public function handle($request, Closure $next)
    {

        $user = Auth::user();

        if (! $user) {
            throw UnauthorizedException::notLoggedIn();
        }

        $routeName = $request->route()->getName();

        try {
            $permission = explode('__', $routeName)[0];

            if ($user->can($permission)) {
                return $next($request);
            }
        } catch (\Exception $e) {
            throw UnauthorizedException::forPermissions([$permission]);
        }

        response()->json([
            'message' => trans('auth.unauthorized'),
            'errors' => [
                'permission' => ['There is no permission to access this url by given user.'],
            ],
        ], Response::HTTP_UNAUTHORIZED)->throwResponse();
    }
}
