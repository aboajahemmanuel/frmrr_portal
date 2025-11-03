<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Process subscription payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe_payment(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'plan_id' => 'required|exists:subscription_plans,id'
            ]);

            $user = Auth::user();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $plan = SubscriptionPlan::findOrFail($request->plan_id);

            // Create subscription record
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addDays($plan->duration);

            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->subscription_plan_id = $plan->id;
            $subscription->start_date = $startDate;
            $subscription->end_date = $endDate;
            $subscription->save();

            // Prepare and encrypt payment parameters
            $encryptedParams = $this->paymentService->preparePaymentParams(
                $user->email,
                $user->name,
                $plan->price,
                $user->phone,
                '03014444' // Special service code for subscriptions
            );

            // Get payment URL and redirect
            $redirectUrl = $this->paymentService->getPaymentRedirectUrl($encryptedParams);
            
            return redirect($redirectUrl);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process subscription payment: ' . $e->getMessage())
                ->withInput();
        }
    }
}