<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    use HasFactory;

    protected $fillable = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }
}
