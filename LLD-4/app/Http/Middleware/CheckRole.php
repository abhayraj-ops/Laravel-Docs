<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('role')) {
            return response()->json([
                'error' => 'Role not found in session'
            ], 401);
        }

        $role = $request->session()->get('role');

        if ($role == 'admin') {
            return $next($request);
        } else {
            return response()->json(['message' => "Users cannot access this route only admin."], 401);
        }
    }
}
