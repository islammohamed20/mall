<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('floor')->orderBy('sort_order')->latest()->paginate(20);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $floors = Floor::orderBy('sort_order')->get();
        return view('admin.units.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_ar'         => 'required|string|max:255',
            'title_en'         => 'required|string|max:255',
            'description_ar'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'unit_number'      => 'nullable|string|max:50',
            'floor_id'         => 'nullable|exists:floors,id',
            'area'             => 'nullable|numeric|min:0',
            'price'            => 'nullable|numeric|min:0',
            'price_type'       => 'required|in:sale,rent',
            'currency'         => 'required|string|max:10',
            'status'           => 'required|in:available,reserved,sold,rented',
            'type'             => 'required|in:shop,office,kiosk,storage',
            'image'            => 'nullable|image|max:2048',
            'features_ar'      => 'nullable|string',
            'features_en'      => 'nullable|string',
            'contact_phone'    => 'nullable|string|max:30',
            'contact_email'    => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:30',
            'is_active'        => 'boolean',
            'sort_order'       => 'integer',
        ]);

        $data['slug'] = Str::slug($data['title_en'] ?: $data['title_ar']);
        $count = Unit::where('slug', $data['slug'])->count();
        if ($count) {
            $data['slug'] .= '-' . ($count + 1);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        Unit::create($data);

        return redirect()->route('admin.units.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة الوحدة بنجاح' : 'Unit added successfully');
    }

    public function edit(Unit $unit)
    {
        $floors = Floor::orderBy('sort_order')->get();
        return view('admin.units.edit', compact('unit', 'floors'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'title_ar'         => 'required|string|max:255',
            'title_en'         => 'required|string|max:255',
            'description_ar'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'unit_number'      => 'nullable|string|max:50',
            'floor_id'         => 'nullable|exists:floors,id',
            'area'             => 'nullable|numeric|min:0',
            'price'            => 'nullable|numeric|min:0',
            'price_type'       => 'required|in:sale,rent',
            'currency'         => 'required|string|max:10',
            'status'           => 'required|in:available,reserved,sold,rented',
            'type'             => 'required|in:shop,office,kiosk,storage',
            'image'            => 'nullable|image|max:2048',
            'features_ar'      => 'nullable|string',
            'features_en'      => 'nullable|string',
            'contact_phone'    => 'nullable|string|max:30',
            'contact_email'    => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:30',
            'is_active'        => 'boolean',
            'sort_order'       => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($unit->image) {
                Storage::disk('public')->delete($unit->image);
            }
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $unit->update($data);

        return redirect()->route('admin.units.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الوحدة بنجاح' : 'Unit updated successfully');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->image) {
            Storage::disk('public')->delete($unit->image);
        }
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم حذف الوحدة بنجاح' : 'Unit deleted successfully');
    }
}
