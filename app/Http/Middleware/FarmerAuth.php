<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FarmerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
        public function handle($request, Closure $next)
{
    if (!session()->has('farmer_id')) {
        return redirect()->route('farmer.login')
            ->with('error', 'Please login to continue');
    }

    return $next($request);
}

    }

