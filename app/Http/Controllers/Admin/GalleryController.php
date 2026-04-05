<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with(['shop', 'items'])
            ->latest()
            ->paginate(15);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $shops = Shop::orderBy('name_ar')->get();
        return view('admin.galleries.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['title_en'] ?: $data['title_ar']);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery = Gallery::create($data);

        return redirect()->route('admin.galleries.show', $gallery)->with('status', 'تم إنشاء المعرض بنجاح');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load(['shop', 'items']);
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $shops = Shop::orderBy('name_ar')->get();
        return view('admin.galleries.edit', compact('gallery', 'shops'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'shop_id' => ['nullable', 'exists:shops,id'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['title_en'] ?: $data['title_ar']);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? $gallery->sort_order;

        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.show', $gallery)->with('status', 'تم تحديث المعرض بنجاح');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete cover image
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        // Delete all items
        foreach ($gallery->items as $item) {
            $this->deleteGalleryItem($item);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('status', 'تم حذف المعرض بنجاح');
    }

    public function addItems(Request $request, Gallery $gallery)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi|max:51200',
        ]);

        $items = [];
        foreach ($request->file('items') as $index => $file) {
            $isVideo = in_array($file->getClientOriginalExtension(), ['mp4', 'mov', 'avi']);
            
            $data = [
                'gallery_id' => $gallery->id,
                'type' => $isVideo ? 'video' : 'image',
                'sort_order' => $gallery->items()->count() + $index,
            ];

            if ($isVideo) {
                $data['file_path'] = $file->store('galleries/videos', 'public');
            } else {
                $data['file_path'] = $file->store('galleries/items', 'public');
                
                // Get dimensions
                $imagePath = storage_path('app/public/' . $data['file_path']);
                if (file_exists($imagePath)) {
                    $imageInfo = getimagesize($imagePath);
                    if ($imageInfo) {
                        $data['width'] = $imageInfo[0];
                        $data['height'] = $imageInfo[1];
                    }
                }

                // Create thumbnail
                $thumbnailPath = 'galleries/thumbnails/' . basename($data['file_path']);
                $this->createThumbnail($imagePath, storage_path('app/public/' . $thumbnailPath));
                $data['thumbnail_path'] = $thumbnailPath;
            }

            $items[] = GalleryItem::create($data);
        }

        return redirect()->route('admin.galleries.show', $gallery)->with('status', 'تم إضافة ' . count($items) . ' عنصر بنجاح');
    }

    public function deleteItem(Gallery $gallery, GalleryItem $item)
    {
        if ($item->gallery_id !== $gallery->id) {
            abort(404);
        }

        $this->deleteGalleryItem($item);
        $item->delete();

        return back()->with('status', 'تم حذف العنصر بنجاح');
    }

    private function deleteGalleryItem(GalleryItem $item)
    {
        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }
        if ($item->thumbnail_path) {
            Storage::disk('public')->delete($item->thumbnail_path);
        }
    }

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

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

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

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

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

        imagedestroy($source);
        imagedestroy($thumbnail);

        return true;
    }
}
