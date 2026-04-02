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
    public function index(Request $request)
    {

        $user = Auth::user();

        // ---- Date Range Filter ----
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : Carbon::now()->subWeeks(12)->startOfWeek();
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : Carbon::now()->endOfDay();

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

        // DOCUMENT COUNT
        $all_documents = Regulation::count();
        $active_documents = Regulation::where('status', 1)->count();
        $inactive_documents = Regulation::where('status', '!=', 1)->count();


        // Fetch categories with the count of documents in each (filtered by date range)
        $categories = Category::where('status', 1)->withCount(['documents' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        // Prepare data for the chart
        $chartData = [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('documents_count')->toArray(),
            'colors' => $categories->map(function ($category) {
                return '#' . substr(md5(rand()), 0, 6);
            })->toArray()
        ];

        // Download stats filtered by date range
        $DownloadStats = Download::join('regulations', 'downloads.regulation_id', '=', 'regulations.id')
            ->groupBy('downloads.regulation_id', 'regulations.title')
            ->select('downloads.regulation_id', 'regulations.title', DB::raw('count(*) as total'))
            ->whereBetween('downloads.created_at', [$startDate, $endDate])
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


        // ---- Weekly Downloads Chart Data (filtered by date range) ----
        $weeklyDownloadsRaw = Download::select(
                DB::raw('WEEK(downloads.created_at, 1) as week_number'),
                DB::raw('YEAR(downloads.created_at) as year_number'),
                DB::raw('MIN(downloads.created_at) as week_start'),
                'downloads.regulation_id',
                'regulations.title',
                DB::raw('COUNT(*) as total')
            )
            ->join('regulations', 'downloads.regulation_id', '=', 'regulations.id')
            ->whereBetween('downloads.created_at', [$startDate, $endDate])
            ->groupBy('week_number', 'year_number', 'downloads.regulation_id', 'regulations.title')
            ->orderBy('year_number')
            ->orderBy('week_number')
            ->get();

        // Get the top 10 most downloaded documents in this period
        $topDocIds = Download::whereBetween('created_at', [$startDate, $endDate])
            ->select('regulation_id', DB::raw('COUNT(*) as total'))
            ->groupBy('regulation_id')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('regulation_id')
            ->toArray();

        // Build week labels dynamically based on date range
        $weekLabels = [];
        $weekKeys = [];
        $currentWeekStart = $startDate->copy()->startOfWeek();
        $rangeEnd = $endDate->copy()->endOfWeek();
        
        while ($currentWeekStart->lte($rangeEnd) && count($weekLabels) < 52) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek();
            $label = $currentWeekStart->format('M j') . ' - ' . $weekEnd->format('M j');
            $weekLabels[] = $label;
            $weekKeys[] = $currentWeekStart->year . '-' . $currentWeekStart->weekOfYear;
            $currentWeekStart->addWeek();
        }

        // Build datasets per top document
        $chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2e59d9', '#17a673', '#fd7e14'];
        $weeklyDownloadDatasets = [];
        $topDocTitles = Regulation::whereIn('id', $topDocIds)->pluck('title', 'id');

        foreach ($topDocIds as $index => $docId) {
            $docWeeklyData = [];
            foreach ($weekKeys as $weekKey) {
                $parts = explode('-', $weekKey);
                $yr = $parts[0];
                $wk = $parts[1];
                $match = $weeklyDownloadsRaw->first(function ($item) use ($docId, $yr, $wk) {
                    return $item->regulation_id == $docId && $item->year_number == $yr && $item->week_number == $wk;
                });
                $docWeeklyData[] = $match ? $match->total : 0;
            }
            $weeklyDownloadDatasets[] = [
                'label' => \Illuminate\Support\Str::limit($topDocTitles[$docId] ?? 'Document #' . $docId, 40),
                'data' => $docWeeklyData,
                'backgroundColor' => $chartColors[$index % count($chartColors)],
                'borderColor' => $chartColors[$index % count($chartColors)],
                'borderWidth' => 1,
            ];
        }

        $weeklyDownloadChartData = [
            'labels' => $weekLabels,
            'datasets' => $weeklyDownloadDatasets,
        ];

        // Total downloads per week (for the summary line)
        $totalWeeklyDownloads = [];
        foreach ($weekKeys as $weekKey) {
            $parts = explode('-', $weekKey);
            $yr = $parts[0];
            $wk = $parts[1];
            $total = $weeklyDownloadsRaw->filter(function ($item) use ($yr, $wk) {
                return $item->year_number == $yr && $item->week_number == $wk;
            })->sum('total');
            $totalWeeklyDownloads[] = $total;
        }

        $weeklyTotalChartData = [
            'labels' => $weekLabels,
            'data' => $totalWeeklyDownloads,
        ];

        // Pass the selected dates back to the view for the date picker
        $selectedStartDate = $startDate->format('Y-m-d');
        $selectedEndDate = $endDate->format('Y-m-d');

        return view('admin.dashboard', compact('all_categories', 'active_categories', 'inactive_categories', 'all_sub_categories', 'active_sub_categories', 'inactive_sub_categories', 'all_documents', 'active_documents', 'inactive_documents', 'chartData', 'DownloadStats', 'data', 'regulations', 'categories', 'subcategories', 'entities', 'news_alert', 'users', 'roles', 'groups', 'user_groups', 'weeklyDownloadChartData', 'weeklyTotalChartData', 'selectedStartDate', 'selectedEndDate'));
    }
}
