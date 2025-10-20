<?php

namespace App\Http\Controllers;

use App\Models\DisclaimerAcceptance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DisclaimerController extends Controller
{
    public function show()
    {
        return view('disclaimer');
    }
    
    public function accept(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Store disclaimer acceptance only in session (not in database)
        // This ensures users must accept disclaimer every time they login
        Session::put('disclaimer_accepted', true);

        // Create a new disclaimer acceptance record
        $acceptance = new DisclaimerAcceptance();
        $acceptance->user_id = $user->id;
        $acceptance->ip_address = $request->ip();
        $acceptance->save();

        if(Auth::user()->usertype == 'internal') {
            return redirect()->route('dashboard')->with('success', 'Disclaimer accepted successfully.');
        }
        else {
            return redirect()->route('home')->with('success', 'Disclaimer accepted successfully.');
        }
    }
    
    public function history()
    {
        // Get all disclaimer acceptances for the current user
      return  $acceptances = DisclaimerAcceptance::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('disclaimer_history', compact('acceptances'));
    }
}