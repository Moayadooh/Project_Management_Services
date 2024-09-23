<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole('user') && !$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return $next($request);
    }
}
