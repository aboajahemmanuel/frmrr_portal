<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{


    public function logActivity()
    {
          if (!Auth::user()->hasPermissionTo('Audit-log')) {
            abort(403, 'Unauthorized action.');
        }
        $logs = LogActivity::logActivityLists();
        return view('logActivity', compact('logs'));
    }
}
