<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_document_id',
        'related_document_id',
        'relationship_type',
        'product_type',
        'status',
        'notes',
        'created_by',
        'group_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship to source document
    public function sourceDocument()
    {
        return $this->belongsTo(Regulation::class, 'source_document_id');
    }

    // Relationship to related document
    public function relatedDocument()
    {
        return $this->belongsTo(Regulation::class, 'related_document_id');
    }

    // Relationship to user who created the relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for active relationships
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for relationships by type
    public function scopeByType($query, $type)
    {
        return $query->where('relationship_type', $type);
    }

    // Scope for relationships by product type
    public function scopeByProductType($query, $productType)
    {
        return $query->where('product_type', $productType);
    }
}
