<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisclaimerAcceptance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
    ];

    /**
     * Get the user that accepted the disclaimer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}