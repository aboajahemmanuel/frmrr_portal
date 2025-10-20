<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // Specify the mass-assignable attributes, including 'group_id'
    protected $fillable = ['name', 'guard_name', 'group_id'];

    // Optionally, you can add a custom relationship to the Group model
    public function group()
    {
        return $this->belongsTo(Group::class); // Assuming you have a Group model
    }
}
