<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendSubscriptionRenewalAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-renewal-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal email alerts to users whose subscriptions are expiring soon based on the plan notification days setting.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching CheckSubscriptionRenewalsJob...');

        \App\Jobs\CheckSubscriptionRenewalsJob::dispatch();

        $this->info('Job dispatched successfully.');
    }
}
