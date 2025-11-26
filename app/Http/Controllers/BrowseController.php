<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Regulation;
use App\Models\Subcategory;
use App\Models\Subscription;
use App\Models\Transaction;
//use Unicodeveloper\Paystack\Facades\Paystack;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class BrowseController extends Controller
{
    public function index($slug)
    {

         $data     = Category::where('status', 1)->get();
        $category = Category::where('slug', $slug)->first();

        $news_alert = News::all();


        $years = Year::pluck('name'); 

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();
        
              $reg = Regulation::with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNull('ceased')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        // Load page count for each regulation
        $reg->each(function($regulation) {
            $regulation->page_count = $regulation->page_count;
        });

          $regulations_ceased = Regulation::with(['year', 'entity', 'marketProductTags'])
            ->select('id', 'title', 'ceased', 'ceased_date', 'year_id', 'entity_id', 'category_id')
            ->where('status', 1)
            ->whereNotNull('ceased')
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.index', compact('data', 'news_alert', 'years', 'category', 'reg', 'isSubscribed', 'regulations_ceased', 'formattedStatuses', 'marketProductTags'));
    }

    public function ceasedDoc($slug)
    {

        $data     = Category::where('status', 1)->get();
        $category = Category::where('slug', $slug)->first();

        $news_alert = News::all();

        $alpha = DB::table('regulations')
            ->join('alpha', 'regulations.alpha_id', '=', 'alpha.id')
            ->select('alpha.id', 'alpha.name')
            ->where('category_id', '=', $category->id)
            ->where('regulations.status', 1)
            ->groupBy('alpha.id', 'alpha.name')
            ->get();

        $years = Year::pluck('name'); // Assuming 'year' is the column name
                                      // $years = DB::table('regulations')
                                      //     ->join('years', 'regulations.year_id', '=', 'years.id')
                                      //     ->select('years.id', 'years.name')
                                      //     ->where('category_id', '=', $category->id)
                                      //     ->where('regulations.status', 1)
                                      //     ->groupBy('years.id', 'years.name')
                                      //     ->get();

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $reg = Regulation::with(['year', 'entity', 'category', 'marketProductTags'])
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNotNull('ceased')
            ->get();
            
        // Load page count for each regulation
        $reg->each(function($regulation) {
            $regulation->page_count = $regulation->page_count;
        });

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.ceased', compact('data', 'news_alert', 'alpha', 'years', 'category', 'reg', 'isSubscribed', 'statuses', 'formattedStatuses', 'marketProductTags'));
    }

    public function subCatceasedDoc($slug)
    {

        $years = Year::pluck('name'); // Assuming 'year' is the column name

        $data = Category::where('status', 1)->get();

        $subcategory = SubCategory::where('slug', $slug)->first();

        $category = Category::where('id', $subcategory->category_id)->first();

        // return  $subcategory->id;

        $news_alert = News::all();

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $reg = Regulation::with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->where('status', 1)
            ->whereNotNull('ceased')
            ->where('subcategory_id', $subcategory->id)
            ->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.subceased', compact('data', 'years', 'news_alert', 'category', 'reg', 'subcategory', 'isSubscribed', 'statuses', 'formattedStatuses', 'marketProductTags'));
    }

    public function subCategory($slug)
    {

        $years = Year::pluck('name'); // Assuming 'year' is the column name

        $data = Category::where('status', 1)->get();

        $subcategory = SubCategory::where('slug', $slug)->first();

        $category = Category::where('id', $subcategory->category_id)->first();

        // return  $subcategory->id;

        $news_alert = News::all();

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $reg = Regulation::with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->where('status', 1)
            ->where('subcategory_id', $subcategory->id)
            ->whereNull('ceased')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        // Load page count for each regulation
        $reg->each(function($regulation) {
            $regulation->page_count = $regulation->page_count;
        });

        $subcat_ceased = Regulation::with(['year', 'entity', 'marketProductTags'])
            ->select('id', 'title', 'ceased', 'ceased_date', 'year_id', 'entity_id', 'subcategory_id')
            ->where('status', 1)
            ->whereNotNull('ceased')
            ->where('subcategory_id', $subcategory->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.subcat', compact('data', 'years', 'news_alert', 'category', 'reg', 'isSubscribed', 'subcat_ceased', 'subcategory', 'statuses', 'formattedStatuses', 'marketProductTags'));
    }

    public function search_category(Request $request)
    {

         $years         = Year::pluck('name'); // Assuming 'year' is the column name
        $title         = $request['title'];
        $category_slug = $request['category_slug'];

         $category   = Category::where('slug', $category_slug)->first();
          $data       = Category::where('status', 1)->get();
        $news_alert = News::all();

        $search = Regulation::with(['year', 'entity', 'category', 'marketProductTags'])
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNull('ceased')
            ->get();

        $search_ceased = Regulation::with(['year', 'entity', 'marketProductTags'])
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNotNull('ceased')
            ->get();

        $total    = $search->count();
        $catename = $category->name;    
        $cateslug = $category->slug;

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();
            
        return view('categorypages.category_search', compact('search', 'years', 'title', 'total', 'category', 'cateslug', 'catename', 'data', 'news_alert', 'isSubscribed', 'search_ceased', 'marketProductTags'));
    }

    public function search_subcategory(Request $request)
    {
        $years = Year::pluck('name');
        $title = $request['title'];
        $subcategory_slug = $request['subcategory_slug'];

        $subcategory = Subcategory::where('slug', $subcategory_slug)->first();
        $category = Category::where('id', optional($subcategory)->category_id)->first();
        $data = Category::where('status', 1)->get();
        $news_alert = News::all();

        $search = Regulation::with(['year', 'entity', 'category', 'subcategory', 'marketProductTags'])
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->when($subcategory, function ($q) use ($subcategory) {
                $q->where('subcategory_id', $subcategory->id);
            })
            ->whereNull('ceased')
            ->get();

        $search_ceased = Regulation::with(['year', 'entity', 'marketProductTags'])
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->when($subcategory, function ($q) use ($subcategory) {
                $q->where('subcategory_id', $subcategory->id);
            })
            ->whereNotNull('ceased')
            ->get();

        $total = $search->count();

        $userId = Auth::id();
        $today = Carbon::now();
        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today)
            ->exists();

        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.subcategory_search', compact(
            'search', 'years', 'title', 'total', 'category', 'subcategory', 'data', 'news_alert', 'isSubscribed', 'search_ceased', 'marketProductTags'
        ));
    }

    public function search_category_ceased($slug, $title)
    {

        $title;
        $slug;

        $category   = Category::where('slug', $slug)->first();
        $data       = Category::where('status', 1)->get();
        $news_alert = News::all();

        $search = Regulation::with(['year', 'entity', 'category', 'marketProductTags'])
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNotNull('ceased')
            ->get();

        $total    = $search->count();
        $catename = $category->name;
        $cateslug = $category->slug;

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $years = Year::pluck('name'); // Assuming 'year' is the column name
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.category_ceased', compact('search', 'title', 'years', 'total', 'category', 'cateslug', 'catename', 'data', 'news_alert', 'isSubscribed', 'marketProductTags'));
    }

    public function alphaname($slug, $name)
    {
        $userId = Auth::id();
        // $isSubscribed = Subscription::where('user_id', $userId)->exists();

        $subscription = \App\Models\Subscription::where('user_id', $userId)->where('status', 1)->first();

        // Check if subscription exists and is active
        $isSubscribed = $subscription && $subscription->isActive();

        $alpha = DB::table('alpha')->where('name', $name)->first();
        if (! $alpha) {
            return redirect()->back()->with('error', 'Alpha not found.');
        }

        $category = Category::where('slug', $slug)->first();
        if (! $category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        $regulations = Regulation::where('alpha_id', $alpha->id)
            ->where('status', 1)
            ->where('ceased', 0)
            ->paginate(30);

        $regulations_ceased = Regulation::where('alpha_id', $alpha->id)
            ->where('status', 1)
            ->where('ceased', 1)
            ->paginate(30);

        $data_cat = Category::where('slug', $slug)->first();
        if (! $data_cat) {
            return redirect()->back()->with('error', 'Category data not found.');
        }

        $data       = Category::where('status', 1)->get();
        $news_alert = News::all();

        $alphas = DB::table('regulations')
            ->join('alpha', 'regulations.alpha_id', '=', 'alpha.id')
            ->select('alpha.id', 'alpha.name')
            ->where('category_id', '=', $data_cat->id)
            ->where('regulations.status', 1)
            ->groupBy('alpha.id', 'alpha.name')
            ->get();

        $years = DB::table('regulations')
            ->join('years', 'regulations.year_id', '=', 'years.id')
            ->select('years.id', 'years.name')
            ->where('category_id', '=', $data_cat->id)
            ->where('regulations.status', 1)
            ->groupBy('years.id', 'years.name')
            ->get();
            
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.regulations', compact('regulations', 'alpha', 'alphas', 'data_cat', 'data', 'regulations_ceased', 'category', 'news_alert', 'years', 'isSubscribed', 'marketProductTags'));
    }

    public function yearname($slug, $yname)
    {

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

        $alpha = DB::table('years')->where('name', $yname)->first();

        $category = Category::where('slug', $slug)->first();

        $regulations = Regulation::where('year_id', $alpha->id)
            ->where('status', 1)->paginate(30);

        $regulations_ceased = Regulation::where('alpha_id', $alpha->id)
            ->where('status', 1)->paginate(30);

        $data_cat = Category::where('slug', $slug)->first();

        $data = Category::where('status', 1)->get();

        $news_alert = News::all();

        $alphas = DB::table('regulations')
            ->join('alpha', 'regulations.alpha_id', '=', 'alpha.id')
            ->select('alpha.id', 'alpha.name')
            ->where('category_id', '=', $data_cat->id)
            ->where('regulations.status', 1)
            ->groupBy('alpha.id', 'alpha.name')
            ->get();

        $years = DB::table('regulations')
            ->join('years', 'regulations.year_id', '=', 'years.id')
            ->select('years.id', 'years.name')
            ->where('category_id', '=', $data_cat->id)
            ->where('regulations.status', 1)
            ->groupBy('years.id', 'years.name')
            ->get();
            
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.regulations', compact('regulations', 'alpha', 'alphas', 'data_cat', 'data', 'regulations_ceased', 'category', 'news_alert', 'years', 'isSubscribed', 'marketProductTags'));
    }

    public function regulation($slug)
    {

        $currenturl = URL::full();

        $doc_url = Session::put('currenturl', $currenturl);

        $regulations = Regulation::where('slug', $slug)
            ->where('status', 1)->first();

        return view('categorypages.regulation', compact('regulations'));
    }

    public function payment($slug)
    {

        $document_payment = Regulation::where('slug', $slug)
            ->where('status', 1)->first();

        return view('categorypages.payment', compact('document_payment'));
    }

    public function document_download($slug, $payref)
    {

        //return  $payref;
        //return $chchc =  Auth::user()->id;

        $document_payment = Regulation::where('slug', $slug)
            ->where('status', 1)->first();

        $payment_details = Transaction::where('regulation_id', $document_payment->id)
            ->where('reference', $payref)
            ->where('status', 'success')
            ->where('user_id', Auth::user()->id)->latest()->first();

        if (empty($payment_details)) {
            return Redirect::to('/');
        }{
            $success_ref = $payment_details->reference;
            $user_id     = $payment_details->user_id;
        }

        if ($success_ref == $payref && Auth::user()->id == $user_id) {

            return view('categorypages.document_download', compact('document_payment'));
        } else {

            return Redirect::to('/');
        }
    }

    public function categorysearchcate(Request $request, $category_id)
    {
        // $search = $request->input('title');
        $title = $request['title'];
        //$category = $request['title'];
        $search = Regulation::where('title', 'like', '%' . $title . '%')
            ->where('category_id', $category_id)->paginate(30);
        $total = $search->count();

        if (count($search) == 0) {
            return view('categorypages.categorysearch', ['search' => null, 'title' => $title, 'total' => $total]);
        }

        return view('categorypages.categorysearch', compact('search', 'title', 'total'));
    }

    public function downloads()
    {
        $id   = Auth::user()->id;
        $data = Transaction::where('user_id', $id)->where('reference', '!=', null)->where('status', '=', 'success')->paginate(30);
        return view('categorypages.downloads', compact('data'));
        //return   view('categorypages.downloads');
    }

    public function marketProductTag($slug)
    {
        $data = Category::where('status', 1)->get();
        $marketTag = \App\Models\MarketProductTag::where('slug', $slug)->first();
        
        if (!$marketTag) {
            return redirect()->back()->with('error', 'Market Product Tag not found.');
        }

        $news_alert = News::all();
        $years = Year::pluck('name');

        $userId = Auth::id();
        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today)
            ->exists();

        // Get regulations that have this tag in their market_product_tag field
        $reg = Regulation::with(['year', 'entity', 'category', 'subcategory'])
            ->where('status', 1)
            ->where(function($query) use ($marketTag) {
                $query->where('market_product_tag', 'LIKE', '%' . $marketTag->id . '%')
                      ->orWhereHas('marketProductTags', function($q) use ($marketTag) {
                          $q->where('market_product_tags.id', $marketTag->id);
                      });
            })
            ->whereNull('ceased')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        // Load page count for each regulation
        $reg->each(function($regulation) {
            $regulation->page_count = $regulation->page_count;
        });

        $regulations_ceased = Regulation::with(['year', 'entity'])
            ->select('id', 'title', 'ceased', 'ceased_date', 'year_id', 'entity_id', 'category_id')
            ->where('status', 1)
            ->where(function($query) use ($marketTag) {
                $query->where('market_product_tag', 'LIKE', '%' . $marketTag->id . '%')
                      ->orWhereHas('marketProductTags', function($q) use ($marketTag) {
                          $q->where('market_product_tags.id', $marketTag->id);
                      });
            })
            ->whereNotNull('ceased')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statuses = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);
        
        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.market_tag', compact('data', 'news_alert', 'years', 'marketTag', 'reg', 'isSubscribed', 'regulations_ceased', 'formattedStatuses', 'marketProductTags'));
    }

    public function search_market_tag(Request $request)
    {
        $data = Category::where('status', 1)->get();
        $market_tag_slug = $request->get('market_tag_slug');
        $title = $request->get('title');

        $marketTag = \App\Models\MarketProductTag::where('slug', $market_tag_slug)->first();
        if (!$marketTag) {
            return redirect()->back()->with('error', 'Market Product Tag not found.');
        }

        $news_alert = News::all();
        $years = Year::pluck('name');

        $userId = Auth::id();
        $today = Carbon::now();
        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today)
            ->exists();

        $search = Regulation::with(['year', 'entity', 'category', 'subcategory'])
            ->where('status', 1)
            ->where('title', 'like', '%' . $title . '%')
            ->where(function($query) use ($marketTag) {
                $query->where('market_product_tag', 'LIKE', '%' . $marketTag->id . '%')
                      ->orWhereHas('marketProductTags', function($q) use ($marketTag) {
                          $q->where('market_product_tags.id', $marketTag->id);
                      });
            })
            ->whereNull('ceased')
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $search->count();

        $marketProductTags = \App\Models\MarketProductTag::where('status', 1)
            ->where('admin_status', 1)
            ->orderBy('name')
            ->get();

        return view('categorypages.market_tag_search', compact(
            'data', 'news_alert', 'years', 'marketTag', 'search', 'isSubscribed', 'title', 'total', 'marketProductTags'
        ));
    }

    public function deletedownload(Request $request, $id)
    {
        $document_payment           = Transaction::find($id);
        $document_payment->user_del = $request['user_del'];
        $document_payment->save();
        return redirect()->back()->with('success', 'Document deleted updated successfully.');
    }
}
