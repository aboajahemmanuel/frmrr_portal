<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentService;

class SubscriptionController extends Controller
{


    public function subscribe_payment(Request $request, PaymentService $paymentService)
    {
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Create subscription record
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($plan->duration);

        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->subscription_plan_id = $plan->id;
        $subscription->start_date = $startDate;
        $subscription->end_date = $endDate;
        $subscription->save();

        // Create transaction record
        $transaction = $paymentService->createTransaction($user, $plan);

        // Generate payment URL using PaymentService
        $paymentUrl = $paymentService->createSubscriptionPayment($user, $plan);
        
        return redirect($paymentUrl);
    }

}


