<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketProductTagPending extends Model
{
    use HasFactory;

    protected $table = 'market_product_tags_pending';

    protected $fillable = [
        'market_product_tag_id',
        'name',
        'description',
        'inputer_id',
        'authorizer_id',
        'status',
        'action_type',
        'note'
    ];

    /**
     * Get the market product tag that owns the pending request
     */
    public function marketProductTag()
    {
        return $this->belongsTo(MarketProductTag::class);
    }

    /**
     * Get the user who created the request (inputter)
     */
    public function inputter()
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }

    /**
     * Get the user who authorized the request
     */
    public function authorizer()
    {
        return $this->belongsTo(User::class, 'authorizer_id');
    }
}
