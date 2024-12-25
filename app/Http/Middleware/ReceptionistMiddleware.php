<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the receptionist role
        if (auth()->check() && auth()->user()->role === 3) {
            return $next($request); // Allow the request to proceed if the user is a receptionist
        }

        // Redirect to a 403 error page if the user does not have receptionist access
        return redirect('/403')->with('error', 'Access denied.');
    }
}
