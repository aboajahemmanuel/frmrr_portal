<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionSubTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_tier_id',
        'description',
    ];

    public function tier()
    {
        return $this->belongsTo(SubscriptionTier::class, 'subscription_tier_id');
    }

    public function plans()
    {
        return $this->hasMany(SubscriptionPlan::class, 'subscription_sub_tier_id');
    }
}
