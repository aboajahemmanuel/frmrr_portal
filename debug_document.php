<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Check the specific document with ID 35
    $document = \App\Models\Regulation::find(35);
    
    if ($document) {
        echo "Document ID 35: " . $document->title . "\n";
        echo "nested_related_docs column value: " . ($document->nested_related_docs_column ?? 'NULL') . "\n";
        
        // Check if there are any related documents
        echo "related_docs: " . ($document->related_docs ?? 'NULL') . "\n";
        
        // Test the accessor functionality
        echo "\n--- Testing accessor functionality ---\n";
        $nestedDocsCollection = collect();
        if ($document->nested_related_docs_column) {
            $nestedIds = json_decode($document->nested_related_docs_column, true);
            echo "Decoded nested IDs: ";
            print_r($nestedIds);
            
            if (is_array($nestedIds) && !empty($nestedIds)) {
                echo "Nested documents found:\n";
                foreach ($nestedIds as $id) {
                    $nestedDoc = \App\Models\Regulation::find($id);
                    if ($nestedDoc) {
                        echo "  ID $id: " . $nestedDoc->title . "\n";
                        $nestedDoc->relationship_type = 'Nested Related';
                        $nestedDocsCollection->push($nestedDoc);
                    } else {
                        echo "  ID $id: NOT FOUND\n";
                    }
                }
            }
        } else {
            echo "No nested related docs data found\n";
        }
        
        echo "Collection count: " . $nestedDocsCollection->count() . "\n";
    } else {
        echo "Document with ID 35 not found\n";
    }
    
    // Also check the DocumentApproval records for this document
    echo "\n--- Checking DocumentApproval records ---\n";
    $approvals = \App\Models\DocumentApproval::where('regulation_id', 35)->get();
    echo "Found " . $approvals->count() . " approval records\n";
    
    foreach ($approvals as $approval) {
        echo "Approval ID: " . $approval->id . "\n";
        echo "Action type: " . $approval->action_type . "\n";
        echo "Status: " . $approval->status . "\n";
        echo "temp_nested_related_docs: " . ($approval->temp_nested_related_docs ?? 'NULL') . "\n";
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}