<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('age')) {
            return response()->json([
                'error' => 'Age not found in session'
            ], 401);
        }

        $age = (int) $request->session()->get('age');
        
        if ($age >= 18) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Too Young, come after ' . 18 - $age . " years"], 403);
        }
    }
}
