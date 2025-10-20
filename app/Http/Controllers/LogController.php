<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Illuminate\Http\Request;

class LogController extends Controller
{


    public function logActivity()
    {
        $logs = LogActivity::logActivityLists();
        return view('logActivity', compact('logs'));
    }
}
