<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user() || $request->user()->role !== 'admin'){
            return response()->json(['message'=>'Accès refusè. Admin requis.'], 403);
        }
        return $next($request);
    }
}
