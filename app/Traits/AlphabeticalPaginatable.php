<?php

namespace App\Traits;

use App\Models\Regulation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait AlphabeticalPaginatable
{
    /**
     * Helper method to perform alphabetical pagination
     */
    protected function alphabeticalPaginate($categoryId = null, $subcategoryId = null, $extraQuery = null, $targetPerPage = 20)
    {
        $query = Regulation::where('status', 1);
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        if ($subcategoryId) {
            $query->where('subcategory_id', $subcategoryId);
        }
        
        if ($extraQuery && is_callable($extraQuery)) {
            $extraQuery($query);
        }

        // 1. Get counts of items per first letter
        $countQuery = clone $query;
        $letterCounts = $countQuery->selectRaw('UPPER(LEFT(title, 1)) as letter, count(*) as count')
            ->groupBy('letter')
            ->orderBy('letter')
            ->get();

        $pageGroups = [];
        $currentGroup = ['letters' => [], 'count' => 0];

        foreach ($letterCounts as $lc) {
            $letter = $lc->letter;
            $count = $lc->count;

            if ($currentGroup['count'] >= ($targetPerPage * 0.5)) {
                $underflowIfSplit = $targetPerPage - $currentGroup['count'];
                $overflowIfAdd = ($currentGroup['count'] + $count) - $targetPerPage;
                
                if ($overflowIfAdd >= $underflowIfSplit) {
                    $pageGroups[] = $currentGroup;
                    $currentGroup = ['letters' => [], 'count' => 0];
                }
            }

            $currentGroup['letters'][] = $letter;
            $currentGroup['count'] += $count;

            if ($currentGroup['count'] >= $targetPerPage) {
                $pageGroups[] = $currentGroup;
                $currentGroup = ['letters' => [], 'count' => 0];
            }
        }

        if (!empty($currentGroup['letters'])) {
            $pageGroups[] = $currentGroup;
        }

        $currentPage = request()->get('page', 1);
        $totalItems = $letterCounts->sum('count');
        $totalPages = count($pageGroups);
        
        $currentPage = max(1, min($currentPage, $totalPages ?: 1));
        
        $activeGroup = $pageGroups[$currentPage - 1] ?? ['letters' => [], 'count' => 0];
        $activeLetters = $activeGroup['letters'];

        // 2. Fetch only items for the active letters on this "page"
        $regItems = $query->whereIn(DB::raw('UPPER(LEFT(title, 1))'), $activeLetters)
            ->with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->orderBy('title', 'asc')
            ->get();

        // 3. Create a LengthAwarePaginator
        $logicalPerPage = $totalPages > 0 ? ceil($totalItems / $totalPages) : $targetPerPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $regItems,
            $totalItems,
            $logicalPerPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
