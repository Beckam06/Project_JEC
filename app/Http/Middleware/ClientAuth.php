<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('client_casa')) {
            return redirect()->route('client.login');
        }

        return $next($request);
    }
}