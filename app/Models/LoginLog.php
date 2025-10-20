<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    // app/Models/LoginLog.php

    protected $fillable = [
        'user_id',
        'name',       // Add this to allow mass assignment
        'email',      // Add this to allow mass assignment
        'status',
        'ip_address',
        'message',
    ];
}
