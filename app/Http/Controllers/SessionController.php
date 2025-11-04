<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Refresh the user's session to extend the timeout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        // Regenerate session ID to prevent session fixation
        $request->session()->regenerate();
        
        // Update session activity timestamp
        Session::put('last_activity', time());
        
        return response()->json([
            'success' => true, 
            'message' => 'Session refreshed successfully',
            'timestamp' => time()
        ]);
    }

    /**
     * Check if the session is still valid
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'authenticated' => false], 401);
        }

        return response()->json([
            'success' => true,
            'authenticated' => true,
            'timestamp' => time()
        ]);
    }
}
