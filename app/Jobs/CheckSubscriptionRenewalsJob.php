<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionRenewalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting subscription renewal alert check from job...');

        $plans = SubscriptionPlan::whereNotNull('notification_days')->where('notification_days', '>', 0)->get();

        $count = 0;

        foreach ($plans as $plan) {
            $targetDate = Carbon::now()->addDays($plan->notification_days)->toDateString();

            $subscriptions = Subscription::with('user')
                ->where('subscription_plan_id', $plan->id)
                ->whereIn('status', ['active', '1', 1])
                ->whereDate('end_date', $targetDate)
                ->get();

            foreach ($subscriptions as $subscription) {
                if ($subscription->user && $subscription->user->email) {
                    Mail::to($subscription->user->email)
                        ->queue(new \App\Mail\SubscriptionRenewalAlert($subscription, $plan));
                    $count++;
                    
                    Log::info('Renewal alert queued for user: ' . $subscription->user->email);
                }
            }
        }

        Log::info('Completed subscription renewal alert check from job. Queued ' . $count . ' emails.');
    }
}
