<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\News;
use App\Models\User;
use App\Models\Group;
use App\Models\Entity;
use App\Models\Category;
use App\Models\Download;
use App\Models\Regulation;
use App\Models\Subcategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DocumentApproval;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $currentMonth = date('m');
        $todayrecord = Carbon::today();
        $today = Carbon::now();
        $startOfWeek = $today->startOfWeek()->format('Y-m-d');
        $endOfWeek = $today->endOfWeek()->format('Y-m-d');




        // CATEGORY COUNT
        $all_categories = Category::count();
        $active_categories = Category::where('status', 1)->count();
        $inactive_categories = Category::where('status', '!=', 1)->count();



        // Sub CATEGORY COUNT
        $all_sub_categories = Subcategory::count();
        $active_sub_categories = Subcategory::where('status', 1)->count();
        $inactive_sub_categories = Subcategory::where('status', '!=', 1)->count();


        // Sub CATEGORY COUNT
        $all_documents = Regulation::count();
        $active_documents = Regulation::where('status', 1)->count();
        $inactive_documents = Regulation::where('status', '!=', 1)->count();






        // Fetch categories with the count of documents in each
        $categories = Category::where('status', 1)->withCount('documents')->get();

        // Prepare data for the chart
        $chartData = [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('documents_count')->toArray(),
            'colors' => $categories->map(function ($category) {
                return '#' . substr(md5(rand()), 0, 6); // Random color for each category
            })->toArray()
        ];


        // Fetch the number of downloads for each document
        $downloads = Regulation::withCount('downloads')->get();

        // Prepare data for the chart
        $chartDataDownload = [
            'labels' => $downloads->pluck('title')->toArray(),
            'data' => $downloads->pluck('downloads_count')->toArray(),
            'colors' => $downloads->map(function ($download) {
                return '#' . substr(md5(rand()), 0, 6); // Random color for each document
            })->toArray()
        ];





        $DownloadStats = Download::join('regulations', 'downloads.regulation_id', '=', 'regulations.id')
            ->groupBy('downloads.regulation_id', 'regulations.title') // Group by both regulation_id and regulation title
            ->select('downloads.regulation_id', 'regulations.title', DB::raw('count(*) as total'))
            ->whereMonth('downloads.created_at', $currentMonth)
            ->orderBy('total', 'desc')
            ->get();


        $data = DocumentApproval::orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $regulations = Regulation::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $categories = Category::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $subcategories = Subcategory::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $entities = Entity::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $news_alert = News::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $users = User::where('status', 0)->where('usertype', '=', 'internal')->orderBy('created_at', 'desc')->where('admin_group', $user->group_id)->get();
        $roles = Role::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $groups = Group::where('status', 0)->orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
        $user_groups = Group::all();





        return view('admin.dashboard', compact('all_categories', 'active_categories', 'inactive_categories', 'all_sub_categories', 'active_sub_categories', 'inactive_sub_categories', 'all_documents', 'active_documents', 'inactive_documents', 'chartData', 'chartDataDownload', 'DownloadStats', 'data', 'regulations', 'categories', 'subcategories', 'entities', 'news_alert', 'users', 'roles', 'groups', 'user_groups'));
    }
}
