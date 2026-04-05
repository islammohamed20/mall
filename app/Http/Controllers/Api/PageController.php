<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Get all pages.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json($pages);
    }

    /**
     * Get a specific page.
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return response()->json($page);
    }

    /**
     * Get page by slug.
     */
    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return response()->json($page);
    }

    /**
     * Update page content.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title_ar' => 'string|max:255',
            'title_en' => 'string|max:255',
            'content_ar' => 'nullable|string',
            'content_en' => 'nullable|string',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        $page->update($data);

        return response()->json([
            'message' => 'Page updated successfully',
            'page' => $page
        ]);
    }

    /**
     * Create a new page.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug',
            'content_ar' => 'nullable|string',
            'content_en' => 'nullable|string',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        $page = Page::create($data);

        return response()->json([
            'message' => 'Page created successfully',
            'page' => $page
        ], 201);
    }

    /**
     * Delete a page.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ]);
    }
}
