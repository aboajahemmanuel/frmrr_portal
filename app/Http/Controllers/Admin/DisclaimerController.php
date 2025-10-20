<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisclaimerAcceptance;
use Illuminate\Http\Request;

class DisclaimerController extends Controller
{
    /**
     * Display a listing of disclaimer acceptances.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $acceptances = DisclaimerAcceptance::with('user')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.disclaimers.index', compact('acceptances'));
    }

    /**
     * Display the specified disclaimer acceptance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $acceptance = DisclaimerAcceptance::with('user')->findOrFail($id);
        
        return view('admin.disclaimers.show', compact('acceptance'));
    }
}