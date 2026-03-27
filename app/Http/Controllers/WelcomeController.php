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
           $plans = SubscriptionPlan::where('status', 1)->get();
        $data = Category::where('status', 1)->get();
        $subscriptionPlans = SubscriptionPlan::all();
        $news_alert = News::orderBy('created_at', 'desc')->where('status', 1)->get();
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

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

        return view('welcome', compact('data', 'news_alert', 'subscriptionPlans', 'plans', 'userSubscription', 'marketProductTags'));
    }



    public function newsalert(Request $request)
    {


        $data = Category::where('status', 1)->get();
        $subscriptionPlans = SubscriptionPlan::all();
        $news_alert = News::orderBy('created_at', 'desc')->where('status', 1)->get();
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

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


        return view('news', compact('data', 'news_alert', 'subscriptionPlans', 'userSubscription', 'marketProductTags'));
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
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();
        return view('alert', compact('single_news', 'news_alert', 'other_news', 'data', 'subscriptionPlans', 'userSubscription', 'marketProductTags'));
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
        );
        
        try {
            Mail::send('emails.feedbackemail', $email_data, function ($message) use ($email_data) {
                $message->to('aboajah.emmanuel@fmdqgroup.com')
                    ->replyTo($email_data['email'])
                    ->subject('New Feedback Received: ' . $email_data['subject'])
                    ->from('no-reply@fmdqgroup.com', 'FMRR Portal');
            });

            return redirect()->back()->with('success', 'Thank you for your Feedback.');
        } catch (\Exception $e) {
            // Log::error('Feedback email failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sorry, we encountered a technical issue sending your feedback. Please try again later.');
        }
    }





    public function success_pay(Request $request)
    {

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
