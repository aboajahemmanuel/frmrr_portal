<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking which documents have nested_related_docs:\n";
echo "===============================================\n\n";

// Get all documents that have nested_related_docs data
$documentsWithNested = DB::table('regulations')
    ->whereNotNull('nested_related_docs')
    ->where('nested_related_docs', '!=', '')
    ->where('nested_related_docs', '!=', '[]')
    ->where('nested_related_docs', '!=', 'null')
    ->orderBy('id')
    ->get(['id', 'title', 'nested_related_docs']);

echo "Documents with nested_related_docs:\n";
echo "-----------------------------------\n";
foreach ($documentsWithNested as $doc) {
    echo "ID {$doc->id}: {$doc->title}\n";
    echo "  Nested docs: {$doc->nested_related_docs}\n";
    
    // Decode and show the related document titles
    $nestedIds = json_decode($doc->nested_related_docs, true);
    if (is_array($nestedIds) && !empty($nestedIds)) {
        echo "  Related documents:\n";
        foreach ($nestedIds as $nestedId) {
            $nestedDoc = \App\Models\Regulation::find($nestedId);
            if ($nestedDoc) {
                echo "    - ID {$nestedId}: {$nestedDoc->title}\n";
            } else {
                echo "    - ID {$nestedId}: NOT FOUND\n";
            }
        }
    }
    echo "\n";
}

echo "Total documents with nested_related_docs: " . count($documentsWithNested) . "\n";

// Also check DocumentApproval records that might have temp_nested_related_docs
echo "\n\nChecking DocumentApproval records with temp_nested_related_docs:\n";
echo "===============================================================\n";

$approvalRecords = DB::table('doc_pending')
    ->whereNotNull('temp_nested_related_docs')
    ->where('temp_nested_related_docs', '!=', '')
    ->where('temp_nested_related_docs', '!=', '[]')
    ->where('temp_nested_related_docs', '!=', 'null')
    ->orderBy('regulation_id')
    ->get(['regulation_id', 'action_type', 'status', 'temp_nested_related_docs']);

echo "Approval records with temp_nested_related_docs:\n";
echo "---------------------------------------------\n";
foreach ($approvalRecords as $record) {
    echo "Regulation ID: {$record->regulation_id}, Action: {$record->action_type}, Status: {$record->status}\n";
    echo "  Temp nested docs: {$record->temp_nested_related_docs}\n";
    
    $nestedIds = json_decode($record->temp_nested_related_docs, true);
    if (is_array($nestedIds) && !empty($nestedIds)) {
        echo "  Related documents:\n";
        foreach ($nestedIds as $nestedId) {
            $nestedDoc = \App\Models\Regulation::find($nestedId);
            if ($nestedDoc) {
                echo "    - ID {$nestedId}: {$nestedDoc->title}\n";
            } else {
                echo "    - ID {$nestedId}: NOT FOUND\n";
            }
        }
    }
    echo "\n";
}

echo "Total approval records with temp_nested_related_docs: " . count($approvalRecords) . "\n";