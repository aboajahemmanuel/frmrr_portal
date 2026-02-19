<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MarketProductTag;
use App\Models\Regulation;

// Get the market tag
$marketTag = MarketProductTag::find(22); // Equities tag ID from your log

if (!$marketTag) {
    echo "Market tag not found!\n";
    exit;
}

echo "=== Market Tag Info ===\n";
echo "ID: " . $marketTag->id . "\n";
echo "Name: " . $marketTag->name . "\n";
echo "Slug: " . $marketTag->slug . "\n";
echo "Status: " . $marketTag->status . "\n";
echo "Admin Status: " . $marketTag->admin_status . "\n\n";

// Get regulations via relationship method
$relationshipRegs = Regulation::whereHas('marketProductTags', function($q) use ($marketTag) {
    $q->where('market_product_tags.id', $marketTag->id);
})->with(['year', 'entity', 'category', 'subcategory'])->get();

echo "=== Regulations with Relationship Tag ===\n";
echo "Count: " . $relationshipRegs->count() . "\n\n";

foreach ($relationshipRegs as $reg) {
    echo "Regulation ID: " . $reg->id . "\n";
    echo "Title: " . $reg->title . "\n";
    echo "Status: " . $reg->status . "\n";
    echo "Ceased: " . ($reg->ceased === null ? 'NULL' : $reg->ceased) . "\n";
    echo "Ceased Date: " . ($reg->ceased_date ?? 'NULL') . "\n";
    echo "Year: " . ($reg->year ? $reg->year->name : 'NULL') . "\n";
    echo "Entity: " . ($reg->entity ? $reg->entity->name : 'NULL') . "\n";
    echo "Category: " . ($reg->category ? $reg->category->name : 'NULL') . "\n";
    echo "---\n";
}

// Test the actual filtering logic
echo "\n=== Testing Filter Logic ===\n";
$filtered = Regulation::with(['year', 'entity', 'category', 'subcategory'])
    ->where('status', 1)
    ->where(function($query) use ($marketTag) {
        $query->where('market_product_tag', 'LIKE', '%' . $marketTag->id . '%')
              ->orWhereHas('marketProductTags', function($q) use ($marketTag) {
                  $q->where('market_product_tags.id', $marketTag->id);
              });
    })
    ->where(function ($query) {
        $query->where(function($q) {
            $q->whereNull('ceased')
              ->orWhere('ceased', 'Active')
              ->orWhere('ceased', 'NULL')
              ->orWhere('ceased', '')
              ->orWhere('ceased', 'LIKE', '%Active%');
        });
    })
    ->orderBy('created_at', 'desc')
    ->get();

echo "Filtered results count: " . $filtered->count() . "\n";

if ($filtered->count() == 0 && $relationshipRegs->count() > 0) {
    echo "\n=== ISSUE IDENTIFIED ===\n";
    echo "Found regulations with the tag, but they're being filtered out.\n";
    echo "Most likely causes:\n";
    echo "1. Status is not 1\n";
    echo "2. Ceased field is not NULL (might be empty string or other value)\n";
    echo "3. The ceased condition logic is excluding them\n\n";
    
    foreach ($relationshipRegs as $reg) {
        echo "Checking regulation ID " . $reg->id . ":\n";
        echo "  Status: " . $reg->status . " (should be 1)\n";
        echo "  Ceased: ";
        var_dump($reg->ceased);
        echo "  Ceased is NULL: " . (is_null($reg->ceased) ? 'YES' : 'NO') . "\n";
        echo "  Ceased is empty string: " . ($reg->ceased === '' ? 'YES' : 'NO') . "\n";
        echo "  Ceased is 'NULL' string: " . ($reg->ceased === 'NULL' ? 'YES' : 'NO') . "\n";
        echo "\n";
    }
}