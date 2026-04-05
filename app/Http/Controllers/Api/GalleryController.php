<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries.
     */
    public function index(Request $request)
    {
        $query = Gallery::with(['shop', 'items']);

        // Filter by shop
        if ($request->has('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        $query->orderBy('sort_order')->orderBy('created_at', 'desc');

        $galleries = $query->paginate($request->get('per_page', 15));

        return response()->json($galleries);
    }

    /**
     * Store a newly created gallery.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'shop_id' => 'nullable|exists:shops,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        // Generate slug
        $data['slug'] = Str::slug($data['title_en'] ?: $data['title_ar']);

        $gallery = Gallery::create($data);

        return response()->json([
            'message' => 'Gallery created successfully',
            'gallery' => $gallery->load(['shop', 'items'])
        ], 201);
    }

    /**
     * Display the specified gallery.
     */
    public function show($id)
    {
        $gallery = Gallery::with(['shop', 'items'])->findOrFail($id);

        return response()->json($gallery);
    }

    /**
     * Update the specified gallery.
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title_ar' => 'string|max:255',
            'title_en' => 'string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'shop_id' => 'nullable|exists:shops,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        // Update slug if title changed
        if (isset($data['title_en']) || isset($data['title_ar'])) {
            $data['slug'] = Str::slug($data['title_en'] ?? $gallery->title_en ?? $data['title_ar'] ?? $gallery->title_ar);
        }

        $gallery->update($data);

        return response()->json([
            'message' => 'Gallery updated successfully',
            'gallery' => $gallery->load(['shop', 'items'])
        ]);
    }

    /**
     * Remove the specified gallery.
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete cover image
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        // Delete all gallery items and their files
        foreach ($gallery->items as $item) {
            if ($item->file_path) {
                Storage::disk('public')->delete($item->file_path);
            }
            if ($item->thumbnail_path) {
                Storage::disk('public')->delete($item->thumbnail_path);
            }
            $item->delete();
        }

        $gallery->delete();

        return response()->json([
            'message' => 'Gallery deleted successfully'
        ]);
    }

    /**
     * Add items to gallery.
     */
    public function addItems(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:image,video',
            'items.*.file' => 'required_if:items.*.type,image|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'items.*.video_file' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
            'items.*.video_url' => 'nullable|url',
            'items.*.title_ar' => 'nullable|string|max:255',
            'items.*.title_en' => 'nullable|string|max:255',
            'items.*.description_ar' => 'nullable|string',
            'items.*.description_en' => 'nullable|string',
            'items.*.sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $items = [];

        foreach ($request->items as $index => $itemData) {
            $data = [
                'gallery_id' => $gallery->id,
                'type' => $itemData['type'],
                'title_ar' => $itemData['title_ar'] ?? null,
                'title_en' => $itemData['title_en'] ?? null,
                'description_ar' => $itemData['description_ar'] ?? null,
                'description_en' => $itemData['description_en'] ?? null,
                'sort_order' => $itemData['sort_order'] ?? $index,
                'video_url' => $itemData['video_url'] ?? null,
            ];

            if ($itemData['type'] === 'image' && isset($itemData['file'])) {
                $file = $itemData['file'];
                $path = $file->store('galleries/items', 'public');
                
                // Get image dimensions using getimagesize
                $imagePath = storage_path('app/public/' . $path);
                if (file_exists($imagePath)) {
                    $imageInfo = getimagesize($imagePath);
                    if ($imageInfo) {
                        $data['width'] = $imageInfo[0];
                        $data['height'] = $imageInfo[1];
                    }
                }
                $data['file_path'] = $path;
                
                // Create thumbnail using GD
                $thumbnailPath = 'galleries/thumbnails/' . basename($path);
                $this->createThumbnail($imagePath, storage_path('app/public/' . $thumbnailPath), 400);
                $data['thumbnail_path'] = $thumbnailPath;
            } elseif ($itemData['type'] === 'video' && isset($itemData['video_file'])) {
                $data['file_path'] = $itemData['video_file']->store('galleries/videos', 'public');
            }

            $items[] = GalleryItem::create($data);
        }

        return response()->json([
            'message' => 'Items added to gallery successfully',
            'items' => $items
        ], 201);
    }

    /**
     * Remove item from gallery.
     */
    public function removeItem($galleryId, $itemId)
    {
        $item = GalleryItem::where('gallery_id', $galleryId)->findOrFail($itemId);

        // Delete files
        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }
        if ($item->thumbnail_path) {
            Storage::disk('public')->delete($item->thumbnail_path);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item removed successfully'
        ]);
    }

    /**
     * Update gallery item.
     */
    public function updateItem(Request $request, $galleryId, $itemId)
    {
        $item = GalleryItem::where('gallery_id', $galleryId)->findOrFail($itemId);

        $validator = Validator::make($request->all(), [
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($validator->validated());

        return response()->json([
            'message' => 'Item updated successfully',
            'item' => $item
        ]);
    }

    /**
     * Create thumbnail for image
     */
    private function createThumbnail($sourcePath, $destPath, $maxWidth = 400)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }

        list($width, $height, $type) = $imageInfo;

        // Calculate new dimensions
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create image resource from source
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        // Create new image
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Ensure directory exists
        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Save thumbnail
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnail, $destPath, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnail, $destPath, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($thumbnail, $destPath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($thumbnail, $destPath, 85);
                break;
        }

        // Free memory
        imagedestroy($source);
        imagedestroy($thumbnail);

        return true;
    }
}
