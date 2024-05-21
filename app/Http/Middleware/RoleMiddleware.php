<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponseHelper;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || !$request->user()->hasRole($role)) {
            return JsonResponseHelper::respondErrorForbidden('Access denied!!', 403);
        }

        return $next($request);
    }
}
