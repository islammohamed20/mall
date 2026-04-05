<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries.
     */
    public function index()
    {
        $galleries = Gallery::with(['shop', 'items'])
            ->active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.galleries.index', compact('galleries'));
    }

    /**
     * Display the specified gallery.
     */
    public function show(Gallery $gallery)
    {
        if (!$gallery->is_active) {
            abort(404);
        }

        $gallery->load(['shop', 'items']);

        return view('pages.galleries.show', compact('gallery'));
    }
}
