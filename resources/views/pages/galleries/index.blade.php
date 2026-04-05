@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'المعرض' : 'Gallery')

@push('styles')
<style>
    .masonry-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-auto-rows: 10px;
        gap: 20px;
    }
    
    .masonry-item {
        overflow: hidden;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .masonry-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .masonry-item img, .masonry-item video {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }
    
    .masonry-item .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .masonry-item:hover .overlay {
        opacity: 1;
    }
    
    .video-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    @media (max-width: 768px) {
        .masonry-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
    }
</style>
@endpush

@section('content')
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary-900 dark:text-secondary-50 mb-4">
                {{ app()->getLocale() === 'ar' ? 'معرض الصور والفيديوهات' : 'Gallery' }}
            </h1>
            <p class="text-base md:text-lg text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
                {{ app()->getLocale() === 'ar' ? 'استكشف أحدث الصور والفيديوهات من مول وسط البلد' : 'Explore the latest photos and videos from West Elbalad Mall' }}
            </p>
        </div>

        @if($galleries->count() > 0)
            <!-- Masonry Grid (Behance-style) -->
            <div class="masonry-grid" id="gallery-grid">
                @foreach($galleries as $gallery)
                    <a href="{{ route('gallery.show', $gallery->slug) }}" 
                       class="masonry-item card"
                       style="grid-row-end: span {{ $gallery->items->count() > 0 ? rand(20, 40) : 30 }};">
                        
                        @if($gallery->cover_image_url)
                            <img src="{{ $gallery->cover_image_url }}" 
                                 alt="{{ $gallery->{'title_' . app()->getLocale()} }}"
                                 loading="lazy">
                        @elseif($gallery->items->count() > 0)
                            @php $firstItem = $gallery->items->first(); @endphp
                            @if($firstItem->type === 'image')
                                <img src="{{ $firstItem->file_url }}" 
                                     alt="{{ $gallery->{'title_' . app()->getLocale()} }}"
                                     loading="lazy">
                            @else
                                <video src="{{ $firstItem->file_url }}" 
                                       class="w-full h-auto"
                                       muted
                                       playsinline></video>
                                <div class="video-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                    {{ app()->getLocale() === 'ar' ? 'فيديو' : 'Video' }}
                                </div>
                            @endif
                        @else
                            <div class="bg-gradient-to-br from-primary-100 to-gold-100 dark:from-primary-900/30 dark:to-gold-900/30 h-64 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-secondary-400">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="overlay">
                            <h3 class="font-bold text-white text-lg mb-1">
                                {{ $gallery->{'title_' . app()->getLocale()} }}
                            </h3>
                            @if($gallery->{'description_' . app()->getLocale()})
                                <p class="text-white/90 text-sm line-clamp-2">
                                    {{ $gallery->{'description_' . app()->getLocale()} }}
                                </p>
                            @endif
                            @if($gallery->shop)
                                <p class="text-white/80 text-xs mt-2">
                                    {{ $gallery->shop->{'name_' . app()->getLocale()} }}
                                </p>
                            @endif
                            <p class="text-white/70 text-xs mt-1">
                                {{ $gallery->items->count() }} {{ app()->getLocale() === 'ar' ? 'عنصر' : 'items' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $galleries->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📸</div>
                <h3 class="text-xl font-semibold text-secondary-900 dark:text-secondary-50 mb-2">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد معارض حالياً' : 'No galleries available' }}
                </h3>
                <p class="text-secondary-600 dark:text-secondary-400">
                    {{ app()->getLocale() === 'ar' ? 'تحقق مرة أخرى قريباً' : 'Check back soon for new content' }}
                </p>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    // Masonry grid layout with automatic row span calculation
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('gallery-grid');
        if (!grid) return;

        function updateGrid() {
            const items = grid.querySelectorAll('.masonry-item');
            items.forEach(item => {
                const img = item.querySelector('img, video');
                if (img) {
                    if (img.complete || img.readyState >= 3) {
                        const height = img.offsetHeight;
                        const rowSpan = Math.ceil(height / 10);
                        item.style.gridRowEnd = `span ${rowSpan}`;
                    } else {
                        img.addEventListener('load', function() {
                            const height = img.offsetHeight;
                            const rowSpan = Math.ceil(height / 10);
                            item.style.gridRowEnd = `span ${rowSpan}`;
                        });
                    }
                }
            });
        }

        updateGrid();
        window.addEventListener('resize', updateGrid);
    });
</script>
@endpush
@endsection
