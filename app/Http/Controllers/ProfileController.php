<?php

namespace App\Http\Controllers;

use App\Models\SaveDoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

 
    public function index()
    {
        $user = Auth::user();

        // Check if user has completed their profile
       // $profileComplete = !empty($user->name) && !empty($user->email) && !empty($user->phone);
        
        // If profile is not complete, show profile completion page
        // If profile is complete, check disclaimer status
        // if ($profileComplete) {
        //     // Check if disclaimer has been accepted
        //     $disclaimerAccepted = Session::get('disclaimer_accepted', false);
        //     $disclaimerAcceptance = $user->disclaimerAcceptance;
            
        //     // If disclaimer accepted, redirect to home
        //     if ($disclaimerAccepted || $disclaimerAcceptance) {
        //         return redirect()->route('home');
        //     }
        // }

        $isSubscribed = Subscription::where('user_id', $user->id)->where('status', 1)->exists();

        $savedDocuments = SaveDoc::with('regulation')->where('user_id', $user->id)->get();
        $downloadedDocuments = Download::with('regulation')->where('user_id', $user->id)->get();

        $userPlan = Subscription::where('user_id', $user->id)->first();
        $docDownloaded = Download::where('user_id', $user->id)->count();
        $docSaved = SaveDoc::where('user_id', $user->id)->count();

        return view('profile', compact('savedDocuments', 'userPlan', 'docSaved', 'docDownloaded', 'downloadedDocuments', 'isSubscribed'));
    }



    public function profilepasssword()
    {
        return view('external.profile_password');
    }



    public function userpasswordupdate(Request $request)
    {
        $user = Auth::user();


        if ($request['password'] != "") {
            if (!(Hash::check($request['password'], Auth::user()->password))) {
                return redirect()->back()->with('error', 'Your current Password does not match');
            }

            if (strcmp($request['password'], $request['new_password']) == 0) {
                return redirect()->back()->with('error', 'New password cannot be the same as current password');
            }

            $validation = $request->validate([
                'password' => 'required',
                'new_password' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    // 'regex:/[@$!%*#?&]/', // must contain a special character
                ],
            ]);

            $user->password = bcrypt($request['new_password']);
            $user->save();
        }
        return redirect()->back()->with('success', 'Password Updated');
    }


    public function updateprofile(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'nullable|string|min:8|confirmed',
            'password' => [
                'nullable',
                'string',
                'confirmed',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!.%*#?&]/', // must contain a special character
            ],
            // 'password' => 'required|string|min:6|confirmed',
            //'password_confirmation' => 'required'
        ]);

        // Update the user's profile
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }


        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
