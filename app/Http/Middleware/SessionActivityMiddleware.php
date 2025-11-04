<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\SessionSetting;

class SessionActivityMiddleware
{
    /**
     * Handle an incoming request and update session activity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Only track activity for authenticated users
        if (Auth::check()) {
            // Update the last activity timestamp
            Session::put('last_activity', time());
            
            // Get timeout from database settings
            $timeoutMinutes = SessionSetting::getCurrentTimeout();
            $regenerationInterval = max(300, ($timeoutMinutes * 60) / 4); // Regenerate every 1/4 of session timeout, minimum 5 minutes
            
            // Regenerate session ID periodically for security
            $lastRegeneration = Session::get('last_regeneration', 0);
            if (time() - $lastRegeneration > $regenerationInterval) {
                $request->session()->regenerate();
                Session::put('last_regeneration', time());
            }
        }

        return $next($request);
    }
}
