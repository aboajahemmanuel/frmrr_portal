<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\LoginCount;
use App\Models\LoginLog;
use App\Models\User;
use App\Models\UsersPending;
use App\Models\VerifyUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function validateLogin(Request $request)
    {
        // Validate email and password fields
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Capture the IP address for logging purposes
        $ipAddress = $request->ip();

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // If the user doesn't exist, log the failed attempt and return an error
        if (! $user) {
            $this->logLoginAttempt(
                null,
                'failed',
                $ipAddress,
                'Invalid email or password',
                $request->email
            );
            return back()->with('error', 'Invalid email or password.');
        }

        // Fetch the latest pending user record
        $user_pending = UsersPending::where('user_id', $user->id)->orderby('id', 'DESC')->first();

        // Check if $user_pending is null before accessing its properties
        if ($user_pending && $user_pending->action_type === 'Disabled' && $user_pending->status == 4) {
            $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Account not active', $user->email);
            return back()->with('error', 'Your account is not active. Please contact the administrator.');
        }

        if ($user_pending && $user_pending->action_type === 'Enable' && $user_pending->status == 0) {
            $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Account not active', $user->email);
            return back()->with('error', 'Your account is not active. Please contact the administrator.');
        }

        // Check if the user's account is locked out
        if ($user->lockout_time) {
            $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Account locked', $user->email);
            return back()->with('error', 'Your account has been locked. Please reset your password.');
        }

        // Verify the password
        if (Hash::check($request->password, $user->password)) {
            // Check if it's an external user and their email is not verified
            if ($user->usertype === 'external' && $user->email_verified_at === null) {
                $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Email not verified', $user->email);
                return redirect(route('login'))->with('error', 'Please verify your email to continue');
            }

            // For internal users, check if they need to change their password
            if ($user->usertype === 'internal' && is_null($user->password_changed_at)) {
                session(['user_email_for_password_change' => $user->email]);
                return redirect(route('password_change'))->with('forcePasswordChange', true);
            }

            // Check if the password needs to be changed due to expiration
            $loginCount = LoginCount::orderby('id', 'DESC')->first();
            if ($user->password_changed_at && now()->diffInDays($user->password_changed_at) >= $loginCount->password_age) {
                session(['user_email_for_password_change' => $user->email]);
                return redirect(route('password_change'))->with('error', 'You must change your password as it has been 30 days since the last update.');
            }

            // Reset failed login attempts on successful login
            $user->failed_logins = 0;
            $user->lockout_time  = null;
            $user->save();

            // Clear any previous disclaimer acceptance from session
            // This ensures users must accept disclaimer every time they login
            Session::forget('disclaimer_accepted');

            // Log the successful login attempt
            $this->logLoginAttempt($user->id, 'success', $ipAddress, 'Login successful', $user->email);

            // Log the user in
            Auth::login($user);
            
            // Check if user has completed profile
            $profileComplete = !empty($user->name) && !empty($user->email) && !empty($user->phone);
            
            // Redirect based on profile completion
            if (!$profileComplete) {
                // Profile not complete, redirect to profile page
                return redirect()->route('profile')->with('warning', 'Please complete your profile before proceeding.');
            } else {
                // Profile complete, redirect to disclaimer page (must accept every login)
                return redirect()->route('disclaimer');
            }
        } else {
            // Increment failed login attempts and check for lockout
            $user->failed_logins += 1;
            $loginCount = LoginCount::orderby('id', 'DESC')->first();

            if ($user->failed_logins >= $loginCount->login_count) {
                $user->lockout_time = now();
                $user->save();
                $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Account locked due to failed attempts', $user->email);
                return back()->with('error', 'Your account has been locked. Please reset your password.');
            }

            // Save the user with the incremented failed logins and log the failed attempt
            $user->save();
            $this->logLoginAttempt($user->id, 'failed', $ipAddress, 'Incorrect email or password', $user->email);
            return back()->with('error', 'Incorrect email or password.');
        }
    }

    // Log login attempts
    protected function logLoginAttempt($userId = null, $status, $ipAddress, $message = null, $email = null)
    {
        LoginLog::create([
            'user_id'    => $userId,
            'name'       => $userId ? User::find($userId)->name : null,
            'email'      => $email, // Log the email even if the user is not found
            'status'     => $status,
            'ip_address' => $ipAddress,
            'message'    => $message,
        ]);
    }

    public function home()
    {
        return view('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'fname'    => ['required', 'string', 'max:255'],
            // 'lname'    => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*.#?&]/',
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $name = $request->fname . ' ' . $request->lname;

        $user = User::create([
            'name'         => $name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'company_name' => $request->company_name,
            'usertype'     => 'external',
            'password'     => bcrypt($request->password),
        ]);

        VerifyUser::create([
            'token'   => Str::random(60),
            'user_id' => $user->id,
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));
        return redirect()->route('login')->with('success', 'Please click on the link sent to your email.');
    }

    public function verifyEmail($token)
    {
        $verifiedUser = VerifyUser::where('token', $token)->first();
        if (isset($verifiedUser)) {
            $user = $verifiedUser->user;
            if (! $user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();
                return \redirect(route('login'))->with('success', 'Your email has been verified');
            } else {
                return \redirect(route('login'))->with('success', 'Your email has already been verified. Login to contine');

                //return \redirect()->back()->with('info', 'Your email has already been verified');
            }
        } else {
            return \redirect(route('login'))->with('error', 'Something went wrong!!');
        }
    }

    public function forgetpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        $user_details = User::where('email', $request->email)->first();

        // email data
        $email_data = [
            'token'     => $token,
            'email'     => $request->email,
            'user_name' => $user_details->name,
        ];

        Mail::send('emails.forgetpassword', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'])
                ->subject('Reset Password')
                ->from('no-reply@fmdqgroup.com', 'Financial Markets Regulations & Rules Repository Portal');
        });

        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function resetpassword($token)
    {
        // Retrieve the email using the token
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        // If no matching record is found, redirect back with an error
        if (! $passwordReset) {
            return redirect()->route('password.request')->with('error', 'Invalid password reset token.');
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $passwordReset->email,
        ]);
    }

    public function resetpasswordsubmit(Request $request)
    {

        $request->validate([
            'email'                 => 'required|email|exists:users',
            'password'              => [
                'required',
                'string',
                'confirmed',
                'min:8',               // must be at least 10 characters in length
                'regex:/[a-z]/',       // must contain at least one lowercase letter
                'regex:/[A-Z]/',       // must contain at least one uppercase letter
                'regex:/[0-9]/',       // must contain at least one digit
                'regex:/[@$!%*.#?&]/', // must contain a special character
            ],
            // 'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (! $updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        // $user = User::where('email', $request->email)
        //     ->update(['password' => Hash::make($request->password)]);

        // DB::table('password_resets')->where(['email' => $request->email])->delete();

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withInput()->withErrors('User not found.');
        }

        // Check if the new password is in any of the last 10 used passwords
        $loginCount    = LoginCount::orderby('id', 'DESC')->first();
        $pastPasswords = $user->passwordHistories()->latest()->take($loginCount->login_history_count)->pluck('password');
        foreach ($pastPasswords as $pastPassword) {
            if (Hash::check($request->password, $pastPassword)) {
                return back()->withErrors(['password' => 'You cannot reuse your last 10 passwords.']);
            }
        }

        // Update the user's password
        $user->password            = Hash::make($request->password);
        $user->password_changed_at = now();
                                     // Reset lockout info
        $user->lockout_time  = null; // Assuming this is the column to track lockout time
        $user->failed_logins = 0;    // Reset failed login attempt count
        $user->save();

        // Add the new password to the history
        $user->passwordHistories()->create(['password' => $user->password]);

        // Delete the password reset record
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('success', 'Your password has been changed!');
    }

    public function adminresetpasswordsubmit(Request $request)
    {
        $request->validate([
            'password'              => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@.$!%*#?&]/',
            ],
            'password_confirmation' => 'required',
        ], [
            'password.required'              => 'The password field is required.',
            'password.string'                => 'The password must be a string.',
            'password.confirmed'             => 'The passwords do not match.',
            'password.min'                   => 'The password must be at least 8 characters.',
            'password.regex'                 => 'The password must include uppercase, lowercase, numeric, and special characters.',
            'password_confirmation.required' => 'Please confirm your password.',
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (! $updatePassword) {
            return back()->withInput()->withErrors('Invalid token!');
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withInput()->withErrors('User not found.');
        }

        // Check if the new password is in any of the last 10 used passwords
        $loginCount    = LoginCount::orderby('id', 'DESC')->first();
        $pastPasswords = $user->passwordHistories()->latest()->take($loginCount->login_history_count)->pluck('password');
        foreach ($pastPasswords as $pastPassword) {
            if (Hash::check($request->password, $pastPassword)) {
                return back()->withErrors(['password' => 'You cannot reuse your last 10 passwords.']);
            }
        }

        // Update the user's password
        $user->password            = Hash::make($request->password);
        $user->password_changed_at = now();
                                     // Reset lockout info
        $user->lockout_time  = null; // Assuming this is the column to track lockout time
        $user->failed_logins = 0;    // Reset failed login attempt count
        $user->save();

        // Add the new password to the history
        $user->passwordHistories()->create(['password' => $user->password]);

        // Delete the password reset record
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('success', 'Your password has been changed and any account locks have been cleared.');
    }


       public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
