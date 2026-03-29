<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::query()->ordered()->paginate(20);
        
        return view('admin.shipping-zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.shipping-zones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'governorate_ar' => ['nullable', 'string', 'max:255'],
            'governorate_en' => ['nullable', 'string', 'max:255'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['order'] = $data['order'] ?? 0;

        ShippingZone::create($data);

        return redirect()->route('admin.shipping-zones.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة المنطقة بنجاح' : 'Shipping zone added successfully');
    }

    public function edit(ShippingZone $shippingZone)
    {
        return view('admin.shipping-zones.edit', compact('shippingZone'));
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'governorate_ar' => ['nullable', 'string', 'max:255'],
            'governorate_en' => ['nullable', 'string', 'max:255'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['order'] = $data['order'] ?? 0;

        $shippingZone->update($data);

        return redirect()->route('admin.shipping-zones.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث المنطقة بنجاح' : 'Shipping zone updated successfully');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();

        return redirect()->route('admin.shipping-zones.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم حذف المنطقة بنجاح' : 'Shipping zone deleted successfully');
    }
}
