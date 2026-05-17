<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveXPoweredByMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Remove the X-Powered-By header from the response if set by Laravel or other libraries
        if (method_exists($response, 'headers')) {
            $response->headers->remove('X-Powered-By');
            $response->headers->remove('Server');
        }

        // Also instruct PHP natively to remove its automatically added header
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }

        return $response;
    }
}
