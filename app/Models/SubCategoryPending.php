<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryPending extends Model
{
    use HasFactory;

    protected $table = 'subcategories_pendings';


    // SubCategoryPending.php
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
