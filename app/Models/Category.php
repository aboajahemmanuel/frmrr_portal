<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'

    ];

    public function regulation()
    {
        return $this->hasOne(Regulation::class);
    }


    public function documents()
    {
        return $this->hasMany(Regulation::class);
    }



    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
