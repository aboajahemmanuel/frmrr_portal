<?php

namespace App\Http\Controllers;

use App\Models\DocumentRelationship;
use App\Models\Regulation;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentRelationshipController extends Controller
{
    /**
     * Display the related documents tab for a regulation
     */
    public function index($id)
    {
        $regulation = Regulation::findOrFail($id);
        $relatedDocuments = $regulation->relatedDocuments();
        $versionHistory = $regulation->versionHistory();
        
        // Get all regulations for linking (excluding current one)
        $allRegulations = Regulation::where('id', '!=', $id)
            ->where('group_id', Auth::user()->group_id)
            ->where('admin_status', 1) // Only approved documents
            ->orderBy('title')
            ->get();

        return view('regulations.related_documents', compact(
            'regulation', 
            'relatedDocuments', 
            'versionHistory', 
            'allRegulations'
        ));
    }

    /**
     * Store a new document relationship
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_document_id' => 'required|exists:regulations,id',
            'related_document_id' => 'required|exists:regulations,id|different:source_document_id',
            'relationship_type' => 'required|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if relationship already exists
        $existingRelationship = DocumentRelationship::where('source_document_id', $request->source_document_id)
            ->where('related_document_id', $request->related_document_id)
            ->where('relationship_type', $request->relationship_type)
            ->first();

        if ($existingRelationship) {
            return redirect()->back()->with('error', 'This relationship already exists.');
        }

        $relationship = DocumentRelationship::create([
            'source_document_id' => $request->source_document_id,
            'related_document_id' => $request->related_document_id,
            'relationship_type' => $request->relationship_type,
            'product_type' => $request->product_type,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'group_id' => Auth::user()->group_id,
        ]);

        LogActivity::addToLog('Document relationship created by ' . Auth::user()->name . 
            ' between documents: ' . $relationship->sourceDocument->title . ' and ' . 
            $relationship->relatedDocument->title);

        return redirect()->back()->with('success', 'Document relationship created successfully.');
    }

    /**
     * Update a document relationship
     */
    public function update(Request $request, $id)
    {
        $relationship = DocumentRelationship::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'relationship_type' => 'required|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $relationship->update([
            'relationship_type' => $request->relationship_type,
            'product_type' => $request->product_type,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        LogActivity::addToLog('Document relationship updated by ' . Auth::user()->name);

        return redirect()->back()->with('success', 'Document relationship updated successfully.');
    }

    /**
     * Delete a document relationship
     */
    public function destroy($id)
    {
        $relationship = DocumentRelationship::findOrFail($id);
        
        LogActivity::addToLog('Document relationship deleted by ' . Auth::user()->name . 
            ' between documents: ' . $relationship->sourceDocument->title . ' and ' . 
            $relationship->relatedDocument->title);

        $relationship->delete();

        return redirect()->back()->with('success', 'Document relationship deleted successfully.');
    }

    /**
     * Link version histories
     */
    public function linkVersionHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_document_id' => 'required|exists:regulations,id',
            'related_document_ids' => 'required|array',
            'related_document_ids.*' => 'exists:regulations,id',
            'relationship_type' => 'required|string|max:255',
            'product_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $relationshipsCreated = 0;

        foreach ($request->related_document_ids as $relatedDocId) {
            if ($relatedDocId != $request->source_document_id) {
                $existingRelationship = DocumentRelationship::where('source_document_id', $request->source_document_id)
                    ->where('related_document_id', $relatedDocId)
                    ->where('relationship_type', $request->relationship_type)
                    ->first();

                if (!$existingRelationship) {
                    DocumentRelationship::create([
                        'source_document_id' => $request->source_document_id,
                        'related_document_id' => $relatedDocId,
                        'relationship_type' => $request->relationship_type,
                        'product_type' => $request->product_type,
                        'created_by' => Auth::id(),
                        'group_id' => Auth::user()->group_id,
                    ]);
                    $relationshipsCreated++;
                }
            }
        }

        LogActivity::addToLog('Version history linked by ' . Auth::user()->name . 
            ' - ' . $relationshipsCreated . ' relationships created');

        return redirect()->back()->with('success', 
            $relationshipsCreated . ' version history relationships created successfully.');
    }

    /**
     * Get relationship types for dropdown
     */
    public function getRelationshipTypes()
    {
        return [
            'Supersedes' => 'Supersedes',
            'Amended' => 'Amended',
            'Ceased' => 'Ceased',
            'Repealed' => 'Repealed',
            'Active Amendment' => 'Active Amendment',
            'Reference' => 'Reference',
            'Related' => 'Related',
            'Superseded By' => 'Superseded By',
            'Amended By' => 'Amended By',
        ];
    }

    /**
     * Get product types for dropdown
     */
    public function getProductTypes()
    {
        return [
            'Bonds' => 'Bonds',
            'Treasury Bills' => 'Treasury Bills',
            'Equities' => 'Equities',
            'Derivatives' => 'Derivatives',
            'Money Market' => 'Money Market',
            'Foreign Exchange' => 'Foreign Exchange',
            'General' => 'General',
        ];
    }
}