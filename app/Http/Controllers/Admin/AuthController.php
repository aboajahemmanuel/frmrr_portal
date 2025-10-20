<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{



    public function adminlogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return redirect()->back()->with('error', 'Incorrect email or password');
    }




    public function adminforgetpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user_details = User::where('email', $request->email)->first();



        // email data
        $email_data = array(
            'token' => $token,
            'email' => $request->email,
            'user_name' => $user_details->name

        );


        Mail::send('emails.forgetpassword', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'])
                ->subject('Reset Password')
                ->from('no-reply@fmdqgroup.com', 'FMDQ Depository Participant Onboarding Portal');
        });


        return back()->with('message', 'We have e-mailed your password reset link!');
    }


    public function adminresetpassword($token)
    {

        return view('auth.passwords.reset', ['token' => $token]);
    }


    public function adminresetpasswordsubmit(Request $request)

    {


        $request->validate([
            'email' => 'required|email|exists:users',
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

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }



        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();


        return redirect('/login')->with('success', 'Your password has been changed!');
    }
}
