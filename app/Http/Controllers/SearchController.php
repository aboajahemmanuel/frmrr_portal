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

        $statuses = \DB::table('doc_type')->get();

        $status            = \DB::table('doc_type')->pluck('name')->toArray();
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
        //  return $request;

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

        // $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        // $formattedStatuses = implode('/', $statuses);

        // Return the view with the results and additional data
        return view('search.searchResult', compact('results', 'Form', 'selectedCategories', 'categories', 'months', 'years', 'date_posted', 'year', 'number', 'entities', 'title', 'isSubscribed'));
    }

    public function searchPostAdvance(Request $request)
    {

        // return $request;
        $query = Regulation::query();

      

        $selectedCategories = $request->input('categories', []);

        if (! empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        $number     = $request->input('number');
        $year_id    = $request->input('year');
        $datePosted = $request->input('date_posted');
        $entity_id  = $request->input('entity_id');

        // Additional filters
        $issueDate      = $request->input('issue_date');
        $effectiveDate  = $request->input('effective_date');
        $ceasedRepealed = $request->input('ceasedRepealed');
        $amended        = $request->input('amended');
        $versionNumber  = $request->input('document_version');
        $entity         = $request->input('entity');
        $searchBy       = $request->input('searchBy');

        $Form = $request->input('Form');
        // Filter by multiple categories if provided
        // if ($request->has('categories') && $request->categories) {
        //     $query->whereIn('category_id', $request->categories);
        // }

        // Filter by keywords and search method if provided
        if ($request->has('search_Words') && $request->search_Words) {
            $keywords     = $request->search_Words;
            $searchMethod = $request->input('searchusing'); // Use input() method to handle default value

            $query->where(function ($q) use ($keywords, $searchMethod) {
                

                if (empty($searchMethod)) {
                    // Handle the case where searchMethod is null
                    $words = explode(' ', $keywords);
                    foreach ($words as $word) {
                        $q->where(function ($query) use ($word) {
                            $query->where('title', 'like', '%' . $word . '%')
                                ->orWhere('document_tag', 'like', '%' . $word . '%');
                        });

                        
                    }
                }

                if ($searchMethod == 'allwords') {
                    $words = explode(' ', $keywords);
                    foreach ($words as $word) {
                        $q->where(function ($query) use ($word) {
                            $query->where('title', 'like', '%' . $word . '%')
                                ->orWhere('document_tag', 'like', '%' . $word . '%');
                        });
                    }
                } elseif ($searchMethod == 'anywords') {
                    $words = explode(' ', $keywords);
                    $q->where(function ($query) use ($words) {
                        foreach ($words as $word) {
                            $query->orWhere('title', 'like', '%' . $word . '%')
                                ->orWhere('document_tag', 'like', '%' . $word . '%');
                        }
                    });
                } elseif ($searchMethod == 'exactwords') {
                    $q->where('title', 'like', '%' . $keywords . '%')
                        ->orWhere('document_tag', 'like', '%' . $keywords . '%');
                } elseif ($searchMethod == 'woutwords') {
                    $words = explode(' ', $keywords);
                    foreach ($words as $word) {
                        $q->where(function ($query) use ($word) {
                            $query->where('title', 'not like', '%' . $word . '%')
                                ->where('document_tag', 'not like', '%' . $word . '%');
                        });
                    }
                }
            });
        }

        if ($request->has('searchBy') && $searchBy == 'tags') {
            $query->where(function ($q) use ($keywords) {
            $q->where('title', 'LIKE', "%{$keywords}%")
            ->orWhere('document_tag', 'LIKE', "%{$keywords}%");
                });

                }

        if ($request->has('searchBy') && $searchBy == 'title') {
           $stopWords = ['on', 'and', 'or', 'of', 'in', 'for', 'to', 'with'];

            // Break the keywords into individual words and remove stop words
            $searchWords = array_filter(explode(' ', trim($keywords)), function ($word) use ($stopWords) {
                return !in_array(strtolower($word), $stopWords);
            });

            // Apply the filtered words to the query
            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%');
                  
                }
            });

           

        }

        if ($request->has('issue_date') && $request->issue_date) {
            $query->where('issue_date', $request->issue_date);
        }

        // Filter by year if provided
        // Apply the year filter if selected
        if ($request->has('year') && $request->year) {
            $query->where('year_id', $year_id);
        }

        // Apply the date posted filter if provided
        // if ($request->has('year') && $request->year) {
        //     $query->whereDate('created_at', $datePosted);
        // }

        // Apply the additional filters
        if ($request->has('issueDate') && $request->issueDate) {
            $query->whereDate('issue_date', $issueDate);
        }

        if ($request->has('effective_date') && $request->effective_date) {
            $query->where('effective_date', $effectiveDate);
        }

        if ($request->has('ceasedRepealed') && $request->ceasedRepealed) {
            $query->where('ceased', '=', $ceasedRepealed);
        }

        if ($request->has('document_version') && $request->document_version) {
            $query->where('document_version', $versionNumber);
        }
        if ($request->has('entity_id') && $request->entity_id) {
             $query->where('entity_id',$entity_id);
        }

        // Apply the category filter if categories are selected
        if (! empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        // Apply the limit if number is provided
        if ($number) {
            $query->limit($number);
        }

        // Get the filtered results
         $results = $query->get();

        $userId = Auth::id();

        // Check if the user is subscribed
        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        // Fetch additional data for the view
        $categories = Category::where('status', 1)->get();
        $months     = DB::table('months')->get();
        $years      = DB::table('years')->get();
        $entities   = Entity::where('status', 1)->get();
        $title      = $request->input('search_Words');

        $statuses = \DB::table('doc_type')->get();

        $status            = \DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $status);

        if ($request->input('ceasedRepealed')) {
            return view('search.AdsearchResultceasedRepealed', compact(
                'results',
                'categories',
                'months',
                'years',
                'entities',
                'title',
                'isSubscribed',
                'issueDate',
                'effectiveDate',
                'ceasedRepealed',
                'versionNumber',
                'year_id',
                'number',
                'entity_id',
                'Form',
                'selectedCategories',
                'searchMethod',
                'statuses',
                'formattedStatuses',
                'status',
                'searchBy'

            ));
        } else {
            return view('search.AdsearchResult', compact(
                'results',
                'categories',
                'months',
                'years',
                'entities',
                'title',
                'isSubscribed',
                'issueDate',
                'effectiveDate',
                'ceasedRepealed',
                'versionNumber',
                'year_id',
                'number',
                'entity_id',
                'Form',
                'selectedCategories',
                'searchMethod',
                'statuses',
                'formattedStatuses',
                'status',
                'searchBy'
            ));
        }

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

        $search = Regulation::where(function ($query) use ($searchWords) {
            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%');
                    // ->orWhere('document_tag', 'like', '%' . $word . '%');
                }
            });
        })
            ->whereNull('ceased')
            ->where('status', 1)
            ->get();

      

        $search_ceased = Regulation::where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->whereNotNull('ceased')
            ->get();

        $total = $search->count();

        $userId = Auth::id();
        $years  = Year::pluck('name'); // Assuming 'year' is the column name

        // $isSubscribed = Subscription::where('user_id', $userId)->where('status', 1)->exists();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        if (count($search) == 0) {
            return view('search.index', ['search' => null, 'years' => $years, 'title' => $title, 'total' => $total, 'isSubscribed', 'search_ceased' => $search_ceased, 'formattedStatuses' => $formattedStatuses]);
        }

        return view('search.index', compact('search', 'years', 'title', 'total', 'isSubscribed', 'search_ceased', 'formattedStatuses'));
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
