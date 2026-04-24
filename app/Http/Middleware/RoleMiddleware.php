<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware  // Rename class to match the file name
{
    public function handle(Request $request, Closure $next)
    {
        // Your middleware logic
        return $next($request);
    }
}