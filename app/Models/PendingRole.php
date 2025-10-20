<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRole extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions', 'role_id', 'inputer_id',  'action_type', 'status'];

    // Cast permissions to an array
    protected $casts = [
        'permissions' => 'array',
    ];
}
