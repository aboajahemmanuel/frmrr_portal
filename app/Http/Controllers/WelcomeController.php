<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $data = Category::where('status', 1)->get();
        $subscriptionPlans = SubscriptionPlan::all();
        $news_alert = News::orderBy('created_at', 'desc')->where('status', 1)->get();

        // Initialize userSubscription to null
        $userSubscription = null;

        // Check if the user is logged in and get the latest active subscription plan
        if (Auth::check()) {
            $user = Auth::user();
            $today = Carbon::now();

            $userSubscription = Subscription::where('user_id', $user->id)
                ->where('status', 1)
                ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
                ->exists();
            // $userSubscription = Subscription::where('user_id', $user->id)->latest('created_at')->first();
        }

        return view('welcome', compact('data', 'news_alert', 'subscriptionPlans', 'userSubscription'));
    }



    public function newsalert(Request $request)
    {


        $data = Category::where('status', 1)->get();
        $subscriptionPlans = SubscriptionPlan::all();
        $news_alert = News::orderBy('created_at', 'desc')->where('status', 1)->get();

        // Initialize userSubscription to null
        $userSubscription = null;

        //$news_alert = News::paginate(15);

        // Check if the user is logged in and get the latest active subscription plan
        if (Auth::check()) {
            $user = Auth::user();
            $today = Carbon::now();

            $userSubscription = Subscription::where('user_id', $user->id)
                ->where('status', 1)
                ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
                ->exists();
            // $userSubscription = Subscription::where('user_id', $user->id)->latest('created_at')->first();
        }


        return view('news', compact('data', 'news_alert', 'subscriptionPlans', 'userSubscription'));
    }



    public function alert(Request $request, $id)
    {

        $data = Category::where('status', 1)->get();
        $subscriptionPlans = SubscriptionPlan::all();
        $news_alert = News::orderBy('created_at', 'desc')->where('status', 1)->get();

        // Initialize userSubscription to null
        $userSubscription = null;

        //$news_alert = News::paginate(15);

        // Check if the user is logged in and get the latest active subscription plan
        if (Auth::check()) {
            $user = Auth::user();
            $today = Carbon::now();

            $userSubscription = Subscription::where('user_id', $user->id)
                ->where('status', 1)
                ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
                ->exists();
            // $userSubscription = Subscription::where('user_id', $user->id)->latest('created_at')->first();
        }




        // return $id;
        $data = Category::where('status', 1)->get();
        $single_news = News::where('id', $id)->first();
        $news_alert = News::where('status', 1)->get();
        $other_news = News::where('status', 1)->limit(4)->get();
        return view('alert', compact('single_news', 'news_alert', 'other_news', 'data', 'subscriptionPlans', 'userSubscription'));
    }


    public function feedback(Request $request)
    {


        // $data = Category::all();
        // $news_alert = News::limit(2)->get();


        return view('contact');
    }

    // public function alert(Request $id)
    // {


    //     return   $news_alert = News::where('id', $id)->get();


    //     return view('alert', compact('news_alert'));
    // }


    public function feedback_post(Request $request)
    {

        $this->validate($request, [
            'fname' => ['required'],
            'lname' => ['required'],
            'email' => ['required'],
            'subject' => ['required'],
            'feedback' => ['required'],
            // 'feedback' => ['required',],

        ]);


        // return $request;
        $email_data = array(
            'fname' => $request['fname'],
            'lname' => $request['lname'],
            'email' => $request['email'],
            'subject' => $request['subject'],
            'feedback' => $request['feedback'],
            'email' => '',
        );
        Mail::send('emails.feedbackemail', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'],)
                ->subject('Feedback')
                ->from('no-reply@fmdqgroup.com', 'Feedback');
        });


        return redirect()->back()->with('success', 'Thank you for your Feedback.');
    }






    public function success_pay(Request $request)
    {


        // // $data = Category::all();
        // //$news_alert = News::all();

        // $data = Category::where('status', 1)->get();
        // $news_alert = News::orderBy('created_at', 'desc')->limit(2)->paginate(15);

        // //$news_alert = News::paginate(15);


        return view('success');
    }



    public function subscribe(Request $request)
    {
        $previousUrl = url()->previous();

        Session::put('previous_url', $previousUrl);

        $getorevioysUrl = Session::get('previous_url');

        $plans = SubscriptionPlan::where('status', 1)->get();

        return view('subscribe', compact('plans'));
    }
}
