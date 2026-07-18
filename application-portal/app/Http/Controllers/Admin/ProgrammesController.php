<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{
    public function index()
    {
        $programmes = json_decode(Setting::get('programmes', '[]'), true) ?? [];
        return view('admin.settings.programmes', compact('programmes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'department' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $programmes = json_decode(Setting::get('programmes', '[]'), true) ?? [];
        $validated['is_active'] = $request->has('is_active');
        $programmes[] = $validated;

        Setting::set('programmes', json_encode($programmes));

        ActivityLog::log('programme_create', "Added programme: {$validated['name']}");

        return back()->with('success', 'Programme added successfully!');
    }

    public function update(Request $request, $index)
    {
        $programmes = json_decode(Setting::get('programmes', '[]'), true) ?? [];

        if (!isset($programmes[$index])) {
            return back()->with('error', 'Programme not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'department' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $programmes[$index] = $validated;

        Setting::set('programmes', json_encode($programmes));

        ActivityLog::log('programme_update', "Updated programme: {$validated['name']}");

        return back()->with('success', 'Programme updated successfully!');
    }

    public function destroy($index)
    {
        $programmes = json_decode(Setting::get('programmes', '[]'), true) ?? [];

        if (!isset($programmes[$index])) {
            return back()->with('error', 'Programme not found.');
        }

        $name = $programmes[$index]['name'];
        array_splice($programmes, $index, 1);

        Setting::set('programmes', json_encode($programmes));

        ActivityLog::log('programme_delete', "Deleted programme: {$name}");

        return back()->with('success', 'Programme deleted successfully!');
    }
}