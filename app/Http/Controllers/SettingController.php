<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::first();
        return view('backend.settings.index',compact('settings'));
    }
    public function store(){
        $settings = Settings::all();
        Settings::where('key','title')->update([
            'value' => request('title')
        ]);
        Settings::where('key','customer_care_number')->update([
            'value' => request('customer_care_number')
        ]);
        Settings::where('key','website_url')->update([
            'value' => request('website_url')
        ]);
        Settings::where('key','app_url')->update([
            'value' => request('app_url')
        ]);
        return redirect()->back()->with('success','Settings updated successfully');
    }
}
