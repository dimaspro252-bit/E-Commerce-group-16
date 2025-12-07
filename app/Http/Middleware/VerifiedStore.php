<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedStore
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user->store) {
            return redirect()->route('seller.register')
                ->with('error', 'Please register your store first.');
        }
        
        if (!$user->store->is_verified) {
            return redirect()->route('dashboard')
                ->with('error', 'Your store is pending verification.');
        }

        return $next($request);
    }
}