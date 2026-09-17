<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'description',
    ];

    public function subTiers()
    {
        return $this->hasMany(SubscriptionSubTier::class, 'subscription_tier_id');
    }
}
