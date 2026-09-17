<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalSubscriptionMember extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_REMOVED = 2;

    public function ownerSubscription()
    {
        return $this->belongsTo(Subscription::class, 'owner_subscription_id');
    }

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_user_id');
    }

    public function memberSubscription()
    {
        return $this->belongsTo(Subscription::class, 'member_subscription_id');
    }
}
