<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entity;
use App\Models\Regulation;
use App\Models\Subscription;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function index()
    {
        

         $categories = Category::all();
        $categories = Category::where('status', 1)->get();
        $months     = DB::table('months')->get();
         $years      = DB::table('years')->get();

        $entities = Entity::where('status', 1)->get();

        $userId = Auth::id();

        // Check if the user is subscribed
        $today = Carbon::now();

        $statuses = DB::table('doc_type')->get();

        $status            = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $status);

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        return view('search.search', compact('categories', 'years', 'months', 'entities', 'isSubscribed', 'formattedStatuses', 'status', 'statuses'));

        //return view('search.search');
    }

    public function searchPost(Request $request)
    {
          //return $request;

        //return   $Form = $request->input('Form');
        $query = Regulation::query();

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('Key_Words') && $request->Key_Words) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->Key_Words . '%')
                    ->orWhere('document_tag', 'like', '%' . $request->Key_Words . '%');
            });
        }

        if ($request->has('year') && $request->year) {
            $query->where('year_id', $request->year);
        }

        if ($request->has('number') && $request->number) {
            $query->limit($request->number);
        }

        if ($request->has('date_posted') && $request->date_posted) {
            $query->whereDate('issue_date', $request->date_posted);
        }

        $results = $query->get();

        $userId = Auth::id();

        // Check if the user is subscribed
        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        // Fetch additional data for the view
        $categories         = Category::where('status', 1)->get();
        $months             = DB::table('months')->get();
        $years              = DB::table('years')->get();
        $entities           = Entity::where('status', 1)->get();
        $title              = $request->input('Key_Words');
        $year               = $request->input('year');
        $Form               = $request->input('Form');
        $number             = $request->input('number');
        $date_posted        = $request->input('date_posted');
        $selectedCategories = $request->input('category_id');

        // Get statuses for the dropdown
        $statuses = DB::table('doc_type')->get();
        $formattedStatuses = implode('/', $statuses->pluck('name')->toArray());

        // Return the view with the results and additional data
        return view('search.searchResult', compact('results', 'Form', 'selectedCategories', 'categories', 'months', 'years', 'date_posted', 'year', 'number', 'entities', 'title', 'isSubscribed', 'statuses', 'formattedStatuses'));
    }

    public function searchPostAdvance(Request $request)
    {
        // Initialize query with eager loading for better performance
        $query = Regulation::with(['year', 'entity', 'category']);

        // Extract all filter parameters
        $filters = [
            'keywords' => $request->input('search_Words'),
            'searchMethod' => $request->input('searchusing'),
            'searchBy' => $request->input('searchBy'),
            'categories' => $request->input('categories', []),
            'year_id' => $request->input('year'),
            'entity_id' => $request->input('entity_id'),
            'issue_date_from' => $request->input('issue_date_from'),
            'issue_date_to' => $request->input('issue_date_to'),
            'effective_date_from' => $request->input('effective_date_from'),
            'effective_date_to' => $request->input('effective_date_to'),
            'ceasedRepealed' => $request->input('ceasedRepealed'),
            'document_version' => $request->input('document_version'),
            'number' => $request->input('number'),
        ];

        // Apply category filter
        if (!empty($filters['categories'])) {
            $query->whereIn('category_id', $filters['categories']);
        }

        // Apply keyword search with different methods
        if (!empty($filters['keywords'])) {
            $this->applyKeywordSearch($query, $filters['keywords'], $filters['searchMethod'], $filters['searchBy']);
        }

        // Apply year filter
        if (!empty($filters['year_id'])) {
            $query->where('year_id', $filters['year_id']);
        }

        // Apply entity filter
        if (!empty($filters['entity_id'])) {
            $query->where('entity_id', $filters['entity_id']);
        }

        // Apply issue date range filter
        if (!empty($filters['issue_date_from']) && !empty($filters['issue_date_to'])) {
            $query->whereDate('issue_date', '>=', $filters['issue_date_from'])
                  ->whereDate('issue_date', '<=', $filters['issue_date_to']);
        } elseif (!empty($filters['issue_date_from'])) {
            $query->whereDate('issue_date', '>=', $filters['issue_date_from']);
        } elseif (!empty($filters['issue_date_to'])) {
            $query->whereDate('issue_date', '<=', $filters['issue_date_to']);
        }

        // Apply effective date range filter
        if (!empty($filters['effective_date_from']) && !empty($filters['effective_date_to'])) {
            $query->whereDate('effective_date', '>=', $filters['effective_date_from'])
                  ->whereDate('effective_date', '<=', $filters['effective_date_to']);
        } elseif (!empty($filters['effective_date_from'])) {
            $query->whereDate('effective_date', '>=', $filters['effective_date_from']);
        } elseif (!empty($filters['effective_date_to'])) {
            $query->whereDate('effective_date', '<=', $filters['effective_date_to']);
        }

        // Apply ceased/repealed status filter
        if (!empty($filters['ceasedRepealed'])) {
            if ($filters['ceasedRepealed'] === 'Active') {
                $query->whereNull('ceased');
            } else {
                // Handle comma-separated values in ceased column
                $query->where(function ($q) use ($filters) {
                    $q->where('ceased', $filters['ceasedRepealed'])
                      ->orWhere('ceased', 'like', '%' . $filters['ceasedRepealed'] . ',%')
                      ->orWhere('ceased', 'like', '%,' . $filters['ceasedRepealed'] . '%');
                });
            }
        }



        // Apply document version filter
        if (!empty($filters['document_version'])) {
            $query->where('document_version', $filters['document_version']);
        }

        // Apply result limit
        if (!empty($filters['number'])) {
            $query->limit($filters['number']);
        }

        // Get filtered results
        $results = $query->get();

        // Check user subscription status
        $isSubscribed = $this->checkUserSubscription();

        // Prepare view data
        $viewData = [
            'results' => $results,
            'categories' => Category::where('status', 1)->get(),
            'months' => DB::table('months')->get(),
            'years' => DB::table('years')->get(),
            'entities' => Entity::where('status', 1)->get(),
            'title' => $filters['keywords'],
            'isSubscribed' => $isSubscribed,
            'issueDateFrom' => $filters['issue_date_from'],
            'issueDateTo' => $filters['issue_date_to'],
            'effectiveDateFrom' => $filters['effective_date_from'],
            'effectiveDateTo' => $filters['effective_date_to'],
            'ceasedRepealed' => $filters['ceasedRepealed'],
            'versionNumber' => $filters['document_version'],
            'year_id' => $filters['year_id'],
            'number' => $filters['number'],
            'entity_id' => $filters['entity_id'],
            'Form' => $request->input('Form'),
            'selectedCategories' => $filters['categories'],
            'searchMethod' => $filters['searchMethod'],
            'searchBy' => $filters['searchBy'],
            'statuses' => DB::table('doc_type')->get(),
            'status' => DB::table('doc_type')->pluck('name')->toArray(),
            'formattedStatuses' => implode('/', DB::table('doc_type')->pluck('name')->toArray()),
        ];

        // Return appropriate view based on ceased/repealed filter
        $view = !empty($filters['ceasedRepealed']) 
            ? 'search.AdsearchResultceasedRepealed' 
            : 'search.AdsearchResult';

        return view($view, $viewData);
    }

    /**
     * Apply keyword search with different search methods
     */
    private function applyKeywordSearch($query, $keywords, $searchMethod = null, $searchBy = null)
    {
        // Define stop words for title search
        $stopWords = ['on', 'and', 'or', 'of', 'in', 'for', 'to', 'with'];

        // Handle search by title with stop words filtering
        if ($searchBy === 'title') {
            $searchWords = array_filter(
                explode(' ', trim($keywords)), 
                fn($word) => !in_array(strtolower($word), $stopWords)
            );

            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%');
                }
            });
            return;
        }

        // Handle search by tags
        if ($searchBy === 'tags') {
            $query->where(function ($q) use ($keywords) {
                $q->where('title', 'LIKE', "%{$keywords}%")
                  ->orWhere('document_tag', 'LIKE', "%{$keywords}%");
            });
            return;
        }

        // Handle different search methods
        $query->where(function ($q) use ($keywords, $searchMethod) {
            switch ($searchMethod) {
                case 'allwords':
                case 'exactwords':
                    // Search for exact phrase in title or tags
                    $q->where('title', 'like', '%' . $keywords . '%')
                      ->orWhere('document_tag', 'like', '%' . $keywords . '%');
                    break;

                case 'anywords':
                    // Search for any of the words
                    $words = explode(' ', $keywords);
                    $q->where(function ($query) use ($words) {
                        foreach ($words as $word) {
                            $query->orWhere('title', 'like', '%' . $word . '%')
                                  ->orWhere('document_tag', 'like', '%' . $word . '%');
                        }
                    });
                    break;

                case 'woutwords':
                    // Exclude documents containing these words
                    $words = explode(' ', $keywords);
                    foreach ($words as $word) {
                        $q->where('title', 'not like', '%' . $word . '%')
                          ->where('document_tag', 'not like', '%' . $word . '%');
                    }
                    break;

                default:
                    // Default: search for all words (AND logic)
                    $words = explode(' ', $keywords);
                    foreach ($words as $word) {
                        $q->where(function ($query) use ($word) {
                            $query->where('title', 'like', '%' . $word . '%')
                                  ->orWhere('document_tag', 'like', '%' . $word . '%');
                        });
                    }
                    break;
            }
        });
    }

    /**
     * Check if the current user has an active subscription
     */
    private function checkUserSubscription()
    {
        $userId = Auth::id();
        if (!$userId) {
            return false;
        }

        return Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', Carbon::now())
            ->exists();
    }

    public function search_result(Request $request)
    {
         $today = Carbon::now();

        //return $search = $request->input('title');
        $title = $request['title'];

        

        $stopWords   = ['on', 'and', 'or', 'of', 'in', 'for', 'to', 'with'];
        $searchWords = array_filter(explode(' ', trim($title)), function ($word) use ($stopWords) {
            return ! in_array(strtolower($word), $stopWords);
        });

        // Store the query parameters for pagination
        $regQuery = Regulation::with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->where(function ($query) use ($searchWords) {
            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%');
                   
                }
            });
        })
            //->whereNull('ceased')
            ->where('status', 1)
            ->orderBy('created_at', 'desc');
            
        // Get the total count before pagination
        $totalRecords = $regQuery->count();
        
        // Apply pagination
        $reg = $regQuery->paginate(30)->appends($request->except('page'));

       
        $total = $reg->total();

        $userId = Auth::id();
        $years  = Year::pluck('name'); 

     
        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) 
            ->exists();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        if (count($reg) == 0) {
            return view('search.index', ['reg' => null, 'years' => $years, 'title' => $title, 'total' => $total, 'isSubscribed' => $isSubscribed, 'formattedStatuses' => $formattedStatuses]);
        }

        return view('search.index', compact('reg', 'years', 'title', 'total', 'isSubscribed', 'formattedStatuses'));
    }

    public function search_result_ceased(Request $request, $search)
    {
        $search;
        $title = $search;

        $search = Regulation::where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->whereNotNull('ceased')
            ->get();

        $total = $search->count();

        $userId = Auth::id();

        $isSubscribed = Subscription::where('user_id', $userId)->where('status', 1)->exists();

        $years = Year::pluck('name'); // Assuming 'year' is the column name

        if (count($search) == 0) {
            return view('search.ceased_result', ['search' => null, 'title' => $title, 'total' => $total, 'isSubscribed']);
        }

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        return view('search.ceased_result', compact('search', 'years', 'title', 'total', 'isSubscribed', 'formattedStatuses', 'statuses'));
    }

  public function search(Request $request)
    {
        // $search = $request->input('title');
        $title  = $request['title'];
        $search = Regulation::where('title', 'like', '%' . $title . '%')->paginate(10);
        $total  = $search->count();

        if (count($search) == 0) {
            return view('search.index', ['search' => null, 'title' => $title, 'total' => $total]);
        }

        return view('search.index', compact('search', 'title', 'total'));
    }

    public function categorysearch(Request $request, $category_slug, $title)
    {
        $catergory = Category::where('slug', $category_slug)->first();

        $search = Regulation::where('title', 'like', '%' . $title . '%')
            ->where('category_id', $catergory->id)->paginate(10);
        $total = $search->count();

        if (count($search) == 0) {
            return view('search.categorysearch', ['search' => null, 'title' => $title, 'total' => $total]);
        }

        return view('search.categorysearch', compact('search', 'title', 'total'));
    }
}
