<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Smalot\PdfParser\Parser as PdfParser;

class Regulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'month_id', 'year_id', 'document_tag', 'alpha_id',
        'entity_id', 'category_id', 'subcategory_id', 'regulation_doc', 'regulation_doc2',
        'price', 'status', 'note', 'ceased', 'effective_date', 'issue_date',
        'document_version', 'ceased_date', 'group_id', 'doc_preview', 'doc_preview_count', 'admin_status',
            ];


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

    // Document relationships - documents that this document relates to
    public function sourceRelationships()
    {
        return $this->hasMany(DocumentRelationship::class, 'source_document_id');
    }

    // Document relationships - documents that relate to this document
    public function relatedRelationships()
    {
        return $this->hasMany(DocumentRelationship::class, 'related_document_id');
    }

    // Get all related documents (both directions)
    public function relatedDocuments()
    {
        $sourceRelationships = $this->sourceRelationships()->with('relatedDocument')->get();
        $relatedRelationships = $this->relatedRelationships()->with('sourceDocument')->get();
        
        $relatedDocs = collect();
        
        foreach ($sourceRelationships as $rel) {
            if ($rel->relatedDocument) {
                $rel->relatedDocument->relationship_type = $rel->relationship_type;
                $rel->relatedDocument->relationship_notes = $rel->notes;
                $relatedDocs->push($rel->relatedDocument);
            }
        }
        
        foreach ($relatedRelationships as $rel) {
            if ($rel->sourceDocument) {
                $rel->sourceDocument->relationship_type = $rel->relationship_type;
                $rel->sourceDocument->relationship_notes = $rel->notes;
                $relatedDocs->push($rel->sourceDocument);
            }
        }
        
        return $relatedDocs->unique('id');
    }

    // Get version history (documents with same title but different versions)
    public function versionHistory()
    {
        return Regulation::where('title', $this->title)
            ->where('id', '!=', $this->id)
            ->orderBy('document_version', 'desc')
            ->get();
    }
    
    // Accessor to get formatted title with effective date
    public function getFormattedTitleAttribute()
    {
        if ($this->effective_date) {
            $formattedDate = \Carbon\Carbon::parse($this->effective_date)->format('M. j, Y');
            return "{$this->title} ({$formattedDate})";
        }
        return $this->title;
    }
    
    // Method to get the page count of the PDF document
    public function getPageCountAttribute()
    {
        try {
            $filePath = public_path("pdf_documents/{$this->regulation_doc}");
            if (!file_exists($filePath)) {
                return 0;
            }
            
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            return $pdf->getDetails()['Pages'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // public function approvals()
    // {
    //     return $this->hasMany(RegulationApproval::class);
    // }
}