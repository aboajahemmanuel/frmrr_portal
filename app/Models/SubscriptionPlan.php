<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    public function subTier()
    {
        return $this->belongsTo(SubscriptionSubTier::class, 'subscription_sub_tier_id');
    }
}
