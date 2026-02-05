<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::query()
            ->ordered()
            ->withCount('shops')
            ->paginate(15);

        return view('admin.floors.index', compact('floors'));
    }

    public function create()
    {
        return view('admin.floors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description_ar' => ['nullable', 'string', 'max:1000'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'map_image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('map_image')) {
            $data['map_image'] = $request->file('map_image')->store('floors', 'public');
        }

        Floor::create($data);

        return redirect()->route('admin.floors.index')->with('status', 'Saved.');
    }

    public function edit(Floor $floor)
    {
        return view('admin.floors.edit', compact('floor'));
    }

    public function update(Request $request, Floor $floor)
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description_ar' => ['nullable', 'string', 'max:1000'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'map_image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('map_image')) {
            if ($floor->map_image) {
                Storage::disk('public')->delete($floor->map_image);
            }
            $data['map_image'] = $request->file('map_image')->store('floors', 'public');
        }

        $floor->update($data);

        return redirect()->route('admin.floors.index')->with('status', 'Saved.');
    }

    public function destroy(Floor $floor)
    {
        if ($floor->shops()->exists()) {
            return back()->with('error', app()->getLocale() === 'ar' 
                ? 'لا يمكن حذف الدور لأنه يحتوي على محلات.' 
                : 'Cannot delete floor because it has shops.');
        }

        if ($floor->map_image) {
            Storage::disk('public')->delete($floor->map_image);
        }

        $floor->delete();

        return redirect()->route('admin.floors.index')->with('status', 'Deleted.');
    }
}
