<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $casts = [
        'end_date' => 'datetime',
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->end_date && $this->end_date->isFuture();
    }

    public function canDownload()
    {
        $limit = $this->subscriptionPlan->download_limit;

        // No limit configured on the plan (the admin Subscription Plan form has no
        // download_limit field, so this is null for every plan created that way)
        // means unlimited downloads, not zero.
        if ($limit === null) {
            return true;
        }

        return $this->download_count < $limit;
    }
}
