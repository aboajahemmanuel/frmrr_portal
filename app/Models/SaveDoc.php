<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveDoc extends Model
{
    use HasFactory;



    protected $table = 'saved_documents';


    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }
}
