<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentApproval extends Model
{
    use HasFactory;

    protected $table = 'doc_pending';
    
    protected $fillable = [
        'regulation_id', 'inputter_id', 'authoriser_id', 'status', 'action_type', 
        'title', 'effective_date', 'issue_date', 'document_version', 'year_id', 
        'month_id', 'entity_id', 'category_id', 'subcategory_id', 'alpha_id', 
        'document_tag', 'ceased_date', 'ceased', 'doc_preview', 'doc_preview_count',
        'related_docs', 'regulation_doc', 'slug', 'group_id', 'authoriser_time', 'inputter_time'
    ];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class, 'regulation_id');
    }


    public function inputter()
    {
        return $this->belongsTo(User::class, 'inputter_id');
    }

    public function authoriser()
    {
        return $this->belongsTo(User::class, 'authoriser_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }


    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }


    public function year()
    {
        return $this->belongsTo(Year::class);
    }



    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function usersWhoSaved()
    {
        return $this->belongsToMany(User::class, 'saved_documents');
    }


    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'regulation_id');
    }


    public function savedDocuments()
    {
        return $this->hasMany(SaveDoc::class);
    }
}