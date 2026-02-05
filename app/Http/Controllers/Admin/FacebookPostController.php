<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\PublishToFacebookJob;
use App\Models\FacebookPost;
use App\Models\FacebookPostOutgoing;
use App\Models\Shop;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacebookPostController extends Controller
{
    public function __construct(
        protected FacebookService $facebookService
    ) {}

    /**
     * Display a listing of incoming Facebook posts (from pages)
     */
    public function index(Request $request)
    {
        $status = $request->string('status')->toString() ?: 'pending';

        $posts = FacebookPost::query()
            ->with(['shop'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.facebook-posts.index', compact('posts', 'status'));
    }

    public function approve(FacebookPost $facebookPost)
    {
        $facebookPost->update(['status' => 'approved']);

        return back()->with('status', 'Approved.');
    }

    public function reject(FacebookPost $facebookPost)
    {
        $facebookPost->update(['status' => 'rejected']);

        return back()->with('status', 'Rejected.');
    }

    // ============================================
    // OUTGOING POSTS (Publish to Facebook Pages)
    // ============================================

    /**
     * Display a listing of outgoing Facebook posts
     */
    public function outgoingIndex(Request $request)
    {
        $query = FacebookPostOutgoing::with(['shop', 'user'])
            ->latest();

        // Filter by shop
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by post type
        if ($request->filled('post_type')) {
            $query->where('post_type', $request->post_type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(20)->withQueryString();
        
        $shops = Shop::whereNotNull('facebook_page_id')
            ->whereNotNull('facebook_page_access_token')
            ->get();

        return view('admin.facebook-posts.outgoing-index', compact('posts', 'shops'));
    }

    /**
     * Show the form for creating a new outgoing post
     */
    public function outgoingCreate()
    {
        $shops = Shop::whereNotNull('facebook_page_id')
            ->whereNotNull('facebook_page_access_token')
            ->get();

        if ($shops->isEmpty()) {
            return redirect()->route('admin.facebook-posts.outgoing.index')
                ->with('error', 'لا توجد متاجر مربوطة بصفحات فيسبوك. قم بإعداد المتاجر أولاً.');
        }

        return view('admin.facebook-posts.outgoing-create', compact('shops'));
    }

    /**
     * Store a newly created outgoing post
     */
    public function outgoingStore(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'post_type' => 'required|in:text,photo,link',
            'message' => 'required_if:post_type,text,link|nullable|string|max:63206',
            'link_url' => 'required_if:post_type,link|nullable|url|max:1000',
            'image' => 'required_if:post_type,photo|nullable|image|max:10240', // 10MB max
            'publish_now' => 'nullable|boolean',
        ], [
            'shop_id.required' => 'يرجى اختيار المتجر',
            'shop_id.exists' => 'المتجر المحدد غير موجود',
            'post_type.required' => 'يرجى اختيار نوع المنشور',
            'message.required_if' => 'يرجى إدخال نص المنشور',
            'link_url.required_if' => 'يرجى إدخال رابط URL',
            'link_url.url' => 'يرجى إدخال رابط صحيح',
            'image.required_if' => 'يرجى رفع صورة',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب أن لا يتجاوز 10 ميجابايت',
        ]);

        // Verify shop has Facebook credentials
        $shop = Shop::find($validated['shop_id']);
        if (!$shop->facebook_page_id || !$shop->facebook_page_access_token) {
            return back()->with('error', 'المتجر المحدد غير مربوط بصفحة فيسبوك')
                ->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('facebook-posts', 'public');
        }

        // Create the post
        $post = FacebookPostOutgoing::create([
            'shop_id' => $validated['shop_id'],
            'user_id' => auth()->id(),
            'post_type' => $validated['post_type'],
            'message' => $validated['message'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'image' => $imagePath,
            'status' => $request->boolean('publish_now') 
                ? FacebookPostOutgoing::STATUS_PENDING 
                : FacebookPostOutgoing::STATUS_DRAFT,
        ]);

        // Dispatch job if publishing now
        if ($request->boolean('publish_now')) {
            PublishToFacebookJob::dispatch($post);
            return redirect()->route('admin.facebook-posts.outgoing.index')
                ->with('success', 'تم إنشاء المنشور وإضافته لقائمة النشر');
        }

        return redirect()->route('admin.facebook-posts.outgoing.index')
            ->with('success', 'تم إنشاء المنشور كمسودة');
    }

    /**
     * Display the specified outgoing post
     */
    public function outgoingShow(FacebookPostOutgoing $post)
    {
        $post->load(['shop', 'user']);
        
        return view('admin.facebook-posts.outgoing-show', compact('post'));
    }

    /**
     * Show the form for editing the outgoing post
     */
    public function outgoingEdit(FacebookPostOutgoing $post)
    {
        // Can't edit published posts
        if ($post->isPublished()) {
            return redirect()->route('admin.facebook-posts.outgoing.show', $post)
                ->with('error', 'لا يمكن تعديل منشور تم نشره');
        }

        $shops = Shop::whereNotNull('facebook_page_id')
            ->whereNotNull('facebook_page_access_token')
            ->get();

        return view('admin.facebook-posts.outgoing-edit', compact('post', 'shops'));
    }

    /**
     * Update the specified outgoing post
     */
    public function outgoingUpdate(Request $request, FacebookPostOutgoing $post)
    {
        // Can't edit published posts
        if ($post->isPublished()) {
            return redirect()->route('admin.facebook-posts.outgoing.show', $post)
                ->with('error', 'لا يمكن تعديل منشور تم نشره');
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'post_type' => 'required|in:text,photo,link',
            'message' => 'required_if:post_type,text,link|nullable|string|max:63206',
            'link_url' => 'required_if:post_type,link|nullable|url|max:1000',
            'image' => 'nullable|image|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('facebook-posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.facebook-posts.outgoing.show', $post)
            ->with('success', 'تم تحديث المنشور بنجاح');
    }

    /**
     * Remove the specified outgoing post
     */
    public function outgoingDestroy(FacebookPostOutgoing $post)
    {
        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.facebook-posts.outgoing.index')
            ->with('success', 'تم حذف المنشور بنجاح');
    }

    /**
     * Publish a draft post
     */
    public function outgoingPublish(FacebookPostOutgoing $post)
    {
        if ($post->isPublished()) {
            return back()->with('error', 'المنشور منشور بالفعل');
        }

        $post->update(['status' => FacebookPostOutgoing::STATUS_PENDING]);
        
        PublishToFacebookJob::dispatch($post);

        return back()->with('success', 'تم إضافة المنشور لقائمة النشر');
    }

    /**
     * Retry a failed post
     */
    public function outgoingRetry(FacebookPostOutgoing $post)
    {
        if (!$post->canRetry()) {
            return back()->with('error', 'لا يمكن إعادة محاولة نشر هذا المنشور');
        }

        $post->update([
            'status' => FacebookPostOutgoing::STATUS_PENDING,
            'error_message' => null,
        ]);

        PublishToFacebookJob::dispatch($post);

        return back()->with('success', 'تم إعادة إضافة المنشور لقائمة النشر');
    }

    /**
     * Verify Facebook connection for a shop
     */
    public function verifyConnection(Shop $shop)
    {
        if (!$shop->facebook_page_id || !$shop->facebook_page_access_token) {
            return response()->json([
                'success' => false,
                'message' => 'المتجر غير مربوط بصفحة فيسبوك',
            ]);
        }

        $result = $this->facebookService->verifyPageToken($shop->facebook_page_access_token);

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => 'الاتصال ناجح',
                'page_name' => $result['page_name'],
                'page_id' => $result['page_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فشل التحقق من الاتصال: ' . ($result['error'] ?? 'خطأ غير معروف'),
        ]);
    }

    /**
     * Delete post from Facebook (if published)
     */
    public function deleteFromFacebook(FacebookPostOutgoing $post)
    {
        if (!$post->isPublished() || !$post->facebook_post_id) {
            return back()->with('error', 'المنشور غير منشور على فيسبوك');
        }

        $shop = $post->shop;
        $deleted = $this->facebookService->deletePost(
            $post->facebook_post_id,
            $shop->facebook_page_access_token
        );

        if ($deleted) {
            $post->update([
                'status' => FacebookPostOutgoing::STATUS_DRAFT,
                'facebook_post_id' => null,
                'facebook_permalink' => null,
                'published_at' => null,
            ]);

            return back()->with('success', 'تم حذف المنشور من فيسبوك');
        }

        return back()->with('error', 'فشل حذف المنشور من فيسبوك');
    }
}
