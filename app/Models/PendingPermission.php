<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingPermission extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'permission_id',
        'inputter_id',
        'status',
        'action_type'
    ];
}
