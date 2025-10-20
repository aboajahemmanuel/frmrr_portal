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

        $alpha = DB::table('regulations')
            ->join('alpha', 'regulations.alpha_id', '=', 'alpha.id')
            ->select('alpha.id', 'alpha.name')
            ->where('category_id', '=', $category->id)
            ->where('regulations.status', 1)
            ->groupBy('alpha.id', 'alpha.name')
            ->get();

        $years = Year::pluck('name'); 

        $userId = Auth::id();

        $today = Carbon::now();

        $isSubscribed = Subscription::where('user_id', $userId)
            ->where('status', 1)
            ->where('end_date', '>=', $today) // Check if the end_date is greater than or equal to today
            ->exists();

          $reg = Regulation::where('status', 1)
            ->where('category_id', $category->id)
            ->whereNull('ceased')
        //->orderByRaw('LOWER(title) asc')
       // ->orderBy('title', 'desc')
            ->get();

         $regulations_ceased = Regulation::where('status', 1)
            ->whereNotNull('ceased')
            ->where('category_id', $category->id)
            ->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        return view('categorypages.index', compact('data', 'news_alert', 'alpha', 'years', 'category', 'reg', 'isSubscribed', 'regulations_ceased', 'formattedStatuses'));
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

        $reg = Regulation::where('status', 1)
            ->where('category_id', $category->id)
            ->whereNotNull('ceased')
            ->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        return view('categorypages.ceased', compact('data', 'news_alert', 'alpha', 'years', 'category', 'reg', 'isSubscribed', 'statuses', 'formattedStatuses'));
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

        $reg = Regulation::where('status', 1)
            ->whereNotNull('ceased')
            ->where('subcategory_id', $subcategory->id)
            ->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        return view('categorypages.subceased', compact('data', 'years', 'news_alert', 'category', 'reg', 'subcategory', 'isSubscribed', 'statuses', 'formattedStatuses'));
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

        $reg = Regulation::where('status', 1)
            ->where('subcategory_id', $subcategory->id)
            ->whereNull('ceased')
            ->get();

        $subcat_ceased = Regulation::where('status', 1)
            ->whereNotNull('ceased')
            ->where('subcategory_id', $subcategory->id)
            ->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        return view('categorypages.subcat', compact('data', 'years', 'news_alert', 'category', 'reg', 'isSubscribed', 'subcat_ceased', 'subcategory', 'statuses', 'formattedStatuses'));
    }

    public function search_category(Request $request)
    {

         $years         = Year::pluck('name'); // Assuming 'year' is the column name
        $title         = $request['title'];
        $category_slug = $request['category_slug'];

         $category   = Category::where('slug', $category_slug)->first();
          $data       = Category::where('status', 1)->get();
        $news_alert = News::all();

        $search = Regulation::where('title', 'like', '%' . $title . '%')
            ->where('status', 1)
            ->where('category_id', $category->id)
            ->whereNull('ceased')
            ->get();

        $search_ceased = Regulation::where('title', 'like', '%' . $title . '%')
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

        return view('categorypages.category_search', compact('search', 'years', 'title', 'total', 'category', 'cateslug', 'catename', 'data', 'news_alert', 'isSubscribed', 'search_ceased'));
    }

    public function search_category_ceased($slug, $title)
    {

        $title;
        $slug;

        $category   = Category::where('slug', $slug)->first();
        $data       = Category::where('status', 1)->get();
        $news_alert = News::all();

        $search = Regulation::where('title', 'like', '%' . $title . '%')
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

        return view('categorypages.category_ceased', compact('search', 'title', 'years', 'total', 'category', 'cateslug', 'catename', 'data', 'news_alert', 'isSubscribed'));
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
            ->paginate(20);

        $regulations_ceased = Regulation::where('alpha_id', $alpha->id)
            ->where('status', 1)
            ->where('ceased', 1)
            ->paginate(20);

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

        return view('categorypages.regulations', compact('regulations', 'alpha', 'alphas', 'data_cat', 'data', 'regulations_ceased', 'category', 'news_alert', 'years', 'isSubscribed'));
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
            ->where('status', 1)->paginate(10);

        $regulations_ceased = Regulation::where('alpha_id', $alpha->id)
            ->where('status', 1)->paginate(20);

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

        return view('categorypages.regulations', compact('regulations', 'alpha', 'alphas', 'data_cat', 'data', 'regulations_ceased', 'category', 'news_alert', 'years', 'isSubscribed'));
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
            ->where('category_id', $category_id)->paginate(10);
        $total = $search->count();

        if (count($search) == 0) {
            return view('categorypages.categorysearch', ['search' => null, 'title' => $title, 'total' => $total]);
        }

        return view('categorypages.categorysearch', compact('search', 'title', 'total'));
    }

    public function downloads()
    {
        $id   = Auth::user()->id;
        $data = Transaction::where('user_id', $id)->where('reference', '!=', null)->where('status', '=', 'success')->paginate(10);
        return view('categorypages.downloads', compact('data'));
        //return   view('categorypages.downloads');
    }

    public function deletedownload(Request $request, $id)
    {
        $document_payment           = Transaction::find($id);
        $document_payment->user_del = $request['user_del'];
        $document_payment->save();
        return redirect()->back()->with('success', 'Document deleted updated successfully.');
    }
}
