<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SessionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionSettingController extends Controller
{
    /**
     * Show the form for editing session settings
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $setting = SessionSetting::first();
        return view('admin.session-settings.edit', compact('setting'));
    }

    /**
     * Update the session settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timeout_minutes' => 'required|integer|in:2,5,10,15,20', // Only allow 10, 15, or 20 minutes
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $setting = SessionSetting::first();
        if (!$setting) {
            $setting = new SessionSetting();
        }
        
        $setting->timeout_minutes = $request->timeout_minutes;
        $setting->save();

        return redirect()->back()->with('success', 'Session timeout setting updated successfully.');
    }
}