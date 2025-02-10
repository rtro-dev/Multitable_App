<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'maxFiles' => 'required|integer|min:1',
        ]);
    
        $setting->update([
            'name' => $request->name,
            'maxFiles' => $request->maxFiles,
        ]);
    
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $setting = Setting::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'maxFiles' => 'required|integer|min:1',
        ]);

        $setting->update([
            'name' => $request->name,
            'maxFiles' => $request->maxFiles,
        ]);

        return redirect()->route('settings.index')->with('success', 'Configuration updated successfully.');
    }
}
