<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgeVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $age =  $request->input('age') ?? $request->header('X-Age');
        if($age == null || $age < 21)
            {
                return response()->json(['Error'=>'Your age should be at least 21 or above.','Provided age'=>$age],Response::HTTP_FORBIDDEN);
            }
        return $next($request);
    }
}
