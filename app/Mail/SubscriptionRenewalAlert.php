<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;

class SubscriptionRenewalAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $plan;

    public function __construct(Subscription $subscription, $plan)
    {
        $this->subscription = $subscription;
        $this->plan = $plan;
    }

    public function build()
    {
        return $this->view('emails.SubscriptionRenewal_Alert')
            ->with([
                'subscription' => $this->subscription,
                'plan' => $this->plan,
            ])
            ->subject('Action Required: Subscription Renewal Alert');
    }
}
