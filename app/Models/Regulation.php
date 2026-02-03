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
        'related_docs', 'nested_related_docs', 'market_product_tag'
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

    public function marketProductTags()
    {
        return $this->belongsToMany(MarketProductTag::class, 'regulation_market_product_tag');
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
    
    // Get related documents as collection with recursive nesting
    public function getRelatedDocumentsAttribute()
    {
        if (!$this->related_docs) {
            return collect();
        }
        
        $relatedIds = explode(',', $this->related_docs);
        $relatedDocuments = Regulation::whereIn('id', $relatedIds)->get();
        
        // Recursively load nested related documents
        $relatedDocuments->each(function ($doc) {
            $doc->nested_related_documents = $this->loadNestedRelatedDocuments($doc, [$this->id]);
        });
        
        return $relatedDocuments;
    }
    

    
    // Recursive method to load nested related documents with circular reference prevention
    private function loadNestedRelatedDocuments($document, $visitedIds = [])
    {
        if (!$document->related_docs || in_array($document->id, $visitedIds)) {
            return collect();
        }
        
        $visitedIds[] = $document->id;
        $nestedIds = explode(',', $document->related_docs);
        $nestedDocuments = Regulation::whereIn('id', $nestedIds)
            ->whereNotIn('id', $visitedIds) // Prevent circular references
            ->get();
        
        // Recursively load nested documents for each nested document
        $nestedDocuments->each(function ($nestedDoc) use ($visitedIds) {
            $nestedDoc->nested_related_documents = $this->loadNestedRelatedDocuments($nestedDoc, $visitedIds);
        });
        
        return $nestedDocuments;
    }
    
    // Get all related documents using the relationship-based approach
    public function getRelatedDocumentsFromRelationshipsAttribute()
    {
        // Get documents that this document is linked to as a source
        $directlyRelated = $this->sourceRelationships()->where('is_active', true)->with('relatedDocument')->get();
        
        $result = collect();
        
        foreach ($directlyRelated as $relationship) {
            if ($relationship->relatedDocument) {
                $relationship->relatedDocument->relationship_type = $relationship->relationship_type;
                $relationship->relatedDocument->relationship_notes = $relationship->notes;
                $result->push($relationship->relatedDocument);
            }
        }
        
        return $result;
    }
    
    // Get all nested related documents using relationship-based approach
    public function getNestedRelatedDocumentsFromRelationshipsAttribute($visitedIds = [])
    {
        if (in_array($this->id, $visitedIds)) {
            return collect();
        }
        
        $visitedIds[] = $this->id;
        
        // Get directly related documents
        $directlyRelated = $this->getRelatedDocumentsFromRelationshipsAttribute();
        
        $nestedCollection = collect();
        
        foreach ($directlyRelated as $relatedDoc) {
            if (!in_array($relatedDoc->id, $visitedIds)) {
                // Add the related document to the collection
                $nestedCollection->push($relatedDoc);
                
                // Recursively get nested related documents
                $nestedRelated = $relatedDoc->getNestedRelatedDocumentsFromRelationshipsAttribute($visitedIds);
                $nestedCollection = $nestedCollection->merge($nestedRelated);
            }
        }
        
        return $nestedCollection->unique('id');
    }
    
    // Combined method to get all related documents (both simple and relationship-based)
    public function getAllRelatedDocumentsAttribute()
    {
        $simpleRelated = $this->related_documents; // Uses the existing accessor
        $relationshipBased = $this->getRelatedDocumentsFromRelationshipsAttribute();
        
        // Combine both collections and return unique documents
        return $simpleRelated->merge($relationshipBased)->unique('id');
    }
    
    // Combined method to get all nested related documents
    public function getAllNestedRelatedDocumentsAttribute($visitedIds = [])
    {
        $simpleNested = $this->nested_related_documents; // Uses existing nested accessor
        $relationshipBasedNested = $this->getNestedRelatedDocumentsFromRelationshipsAttribute($visitedIds);
        
        // Combine both collections and return unique documents
        return $simpleNested->merge($relationshipBasedNested)->unique('id');
    }
    

    
    // Direct accessor for nested_related_docs column
    public function getNestedRelatedDocsColumnAttribute()
    {
        return $this->attributes['nested_related_docs'] ?? null;
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