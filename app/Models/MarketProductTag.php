<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketProductTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'group_id',
        'status',
        'admin_status',
        'note'
    ];

    /**
     * Get the group that owns the tag
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get pending requests for this tag
     */
    public function pendingRequests()
    {
        return $this->hasMany(MarketProductTagPending::class);
    }
}
