<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChnageController extends Controller
{
    public function showChangePasswordForm()
    {
        $email = session('user_email_for_password_change');
        if (!$email) {
            return redirect('login')->withErrors(['error' => 'Unauthorized access.']);
        }

        return view('auth.passwords.change', compact('email'));
    }





    public function changePassword(Request $request)
    {
        $request->validate([

            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!.%*#?&]/', // must contain a special character
            ],
            // 'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $email = session('user_email_for_password_change');
        if (!$email) {
            return redirect('login')->withErrors(['error' => 'Unauthorized access.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect('login')->withErrors(['error' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();

        session()->forget('user_email_for_password_change');

        Auth::login($user); // Optionally log the user in automatically

        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');

        // // Log the user out
        // Auth::logout();

        // // Redirect with success message
        // return redirect('/login')->with('success', 'Your password has been changed successfully. Please log in with your new password.');
    }
}
