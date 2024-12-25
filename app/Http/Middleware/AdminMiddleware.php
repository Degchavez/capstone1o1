<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is an admin
        if (auth()->user() && auth()->user()->role === 0) {
            return $next($request); // Allows the request to proceed if the user is an admin
        }

        // Optionally, return a redirect or error response if the user is not an admin
        return redirect('/403')->with('error', 'You do not have access to this page.');
    }
}
