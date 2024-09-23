<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AddPermissionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user() || !$request->user()->hasPermissionTo('add')) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return $next($request);
    }
}
