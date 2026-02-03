<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate the frontend logic
$result = \App\Models\Regulation::find(35);

echo "Testing frontend logic for document ID 35:\n";
echo "Title: " . $result->title . "\n";

// Get related documents (existing logic)
$relatedDocs = $result->all_related_documents;
echo "Related docs count: " . $relatedDocs->count() . "\n";

if ($relatedDocs instanceof \Illuminate\Support\Collection) {
    $relatedDocs = $relatedDocs->sortByDesc(function ($doc) {
        return \Carbon\Carbon::parse($doc->issue_date);
    });
}

// Get nested related documents from the new column
$nestedRelatedDocsFromColumn = $result->nested_related_docs_column ? collect(json_decode($result->nested_related_docs_column, true)) : collect();
echo "Nested related docs from column count: " . $nestedRelatedDocsFromColumn->count() . "\n";

// Check the condition
$shouldDisplay = ($relatedDocs->count() > 0 || $nestedRelatedDocsFromColumn->count() > 0);
echo "Should display modal: " . ($shouldDisplay ? 'YES' : 'NO') . "\n";

if ($shouldDisplay) {
    echo "\n--- Nested documents that should display ---\n";
    foreach($nestedRelatedDocsFromColumn as $nestedId) {
        $nestedDoc = \App\Models\Regulation::find($nestedId);
        if ($nestedDoc) {
            echo "ID $nestedId: " . $nestedDoc->title . " (Issue: " . $nestedDoc->issue_date . ")\n";
        }
    }
}