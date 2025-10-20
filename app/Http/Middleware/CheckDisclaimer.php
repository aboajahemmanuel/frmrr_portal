<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckDisclaimer
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('disclaimer_accepted')) {
            // return "here";
            return redirect()->route('disclaimer');
        }

        return $next($request);
    }
}
