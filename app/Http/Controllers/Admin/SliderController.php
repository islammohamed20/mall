<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->ordered()
            ->paginate(15);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_ar' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:500'],
            'subtitle_en' => ['nullable', 'string', 'max:500'],
            'cta_text_ar' => ['nullable', 'string', 'max:100'],
            'cta_text_en' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:500'],
            'cta_text_2_ar' => ['nullable', 'string', 'max:100'],
            'cta_text_2_en' => ['nullable', 'string', 'max:100'],
            'cta_link_2' => ['nullable', 'string', 'max:500'],
            'image' => ['required', 'image', 'max:4096'],
            'image_mobile' => ['nullable', 'image', 'max:2048'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->file('image_mobile')->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Saved.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title_ar' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:500'],
            'subtitle_en' => ['nullable', 'string', 'max:500'],
            'cta_text_ar' => ['nullable', 'string', 'max:100'],
            'cta_text_en' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:500'],
            'cta_text_2_ar' => ['nullable', 'string', 'max:100'],
            'cta_text_2_en' => ['nullable', 'string', 'max:100'],
            'cta_link_2' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_mobile' => ['nullable', 'image', 'max:2048'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            if ($slider->image_mobile) {
                Storage::disk('public')->delete($slider->image_mobile);
            }
            $data['image_mobile'] = $request->file('image_mobile')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Saved.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        if ($slider->image_mobile) {
            Storage::disk('public')->delete($slider->image_mobile);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('status', 'Deleted.');
    }
}
