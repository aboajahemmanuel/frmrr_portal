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
        return $this->download_count < $this->subscriptionPlan->download_limit;
    }
}
