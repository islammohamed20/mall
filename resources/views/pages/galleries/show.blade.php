@extends('layouts.app')

@section('title', $gallery->{'title_' . app()->getLocale()})

@push('styles')
<style>
    .gallery-hero {
        height: 400px;
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        margin-bottom: 40px;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-auto-rows: 10px;
        gap: 20px;
    }
    
    .gallery-item {
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: scale(1.02);
    }
    
    .gallery-item img, .gallery-item video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        align-items: center;
        justify-content: center;
    }
    
    .lightbox.active {
        display: flex;
    }
    
    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    
    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        padding: 20px;
        user-select: none;
        z-index: 10000;
    }
    
    .lightbox-prev {
        left: 20px;
    }
    
    .lightbox-next {
        right: 20px;
    }
    
    .video-play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="text-sm mb-4">
                <a href="{{ route('gallery.index') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                    {{ app()->getLocale() === 'ar' ? '← العودة للمعرض' : '← Back to Gallery' }}
                </a>
            </nav>
            
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary-900 dark:text-secondary-50 mb-4">
                {{ $gallery->{'title_' . app()->getLocale()} }}
            </h1>
            
            @if($gallery->{'description_' . app()->getLocale()})
                <p class="text-base md:text-lg text-secondary-600 dark:text-secondary-400 max-w-3xl">
                    {{ $gallery->{'description_' . app()->getLocale()} }}
                </p>
            @endif
            
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-secondary-500 dark:text-secondary-400">
                @if($gallery->shop)
                    <a href="{{ route('shops.show', $gallery->shop->slug) }}" class="flex items-center gap-2 hover:text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        {{ $gallery->shop->{'name_' . app()->getLocale()} }}
                    </a>
                @endif
                
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    {{ $gallery->items->count() }} {{ app()->getLocale() === 'ar' ? 'عنصر' : 'items' }}
                </span>
            </div>
        </div>

        @if($gallery->items->count() > 0)
            <!-- Gallery Grid -->
            <div class="gallery-grid" id="gallery-items-grid">
                @foreach($gallery->items->sortBy('sort_order') as $index => $item)
                    <div class="gallery-item card" 
                         data-index="{{ $index }}"
                         style="grid-row-end: span {{ rand(20, 40) }};">
                        @if($item->type === 'image')
                            <img src="{{ $item->file_url }}" 
                                 alt="{{ $item->{'title_' . app()->getLocale()} ?? $gallery->{'title_' . app()->getLocale()} }}"
                                 loading="lazy"
                                 onclick="openLightbox({{ $index }})">
                        @else
                            <div class="relative" onclick="openLightbox({{ $index }})">
                                <video src="{{ $item->file_url }}"
                                       poster="{{ $item->thumbnail_url }}"
                                       class="w-full h-auto"></video>
                                <div class="video-play-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        
                        @if($item->{'title_' . app()->getLocale()} || $item->{'description_' . app()->getLocale()})
                            <div class="p-4">
                                @if($item->{'title_' . app()->getLocale()})
                                    <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 text-sm">
                                        {{ $item->{'title_' . app()->getLocale()} }}
                                    </h3>
                                @endif
                                @if($item->{'description_' . app()->getLocale()})
                                    <p class="text-xs text-secondary-600 dark:text-secondary-400 mt-1">
                                        {{ $item->{'description_' . app()->getLocale()} }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Lightbox -->
            <div id="lightbox" class="lightbox">
                <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
                <span class="lightbox-nav lightbox-prev" onclick="changeSlide(-1)">&#10094;</span>
                <span class="lightbox-nav lightbox-next" onclick="changeSlide(1)">&#10095;</span>
                <div id="lightbox-media-container"></div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📁</div>
                <p class="text-secondary-600 dark:text-secondary-400">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد عناصر في هذا المعرض' : 'No items in this gallery yet' }}
                </p>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    const galleryItems = @json($gallery->items->sortBy('sort_order')->values()->map(function($item) {
        return [
            'type' => $item->type,
            'file_url' => $item->file_url,
            'thumbnail_url' => $item->thumbnail_url,
            'title_ar' => $item->title_ar,
            'title_en' => $item->title_en,
        ];
    }));
    
    let currentIndex = 0;
    const locale = '{{ app()->getLocale() }}';
    
    function openLightbox(index) {
        currentIndex = index;
        showSlide(index);
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = 'auto';
        const container = document.getElementById('lightbox-media-container');
        container.innerHTML = '';
    }
    
    function changeSlide(direction) {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = galleryItems.length - 1;
        if (currentIndex >= galleryItems.length) currentIndex = 0;
        showSlide(currentIndex);
    }
    
    function showSlide(index) {
        const item = galleryItems[index];
        const container = document.getElementById('lightbox-media-container');
        
        if (item.type === 'image') {
            container.innerHTML = `<img src="${item.file_url}" class="lightbox-content" alt="${item['title_' + locale] || ''}">`;
        } else {
            container.innerHTML = `<video src="${item.file_url}" class="lightbox-content" controls autoplay></video>`;
        }
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('lightbox').classList.contains('active')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        }
    });
    
    // Masonry grid layout
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('gallery-items-grid');
        if (!grid) return;

        function updateGrid() {
            const items = grid.querySelectorAll('.gallery-item');
            items.forEach(item => {
                const media = item.querySelector('img, video');
                if (media) {
                    if (media.complete || media.readyState >= 3) {
                        const height = media.offsetHeight;
                        const rowSpan = Math.ceil(height / 10);
                        item.style.gridRowEnd = `span ${rowSpan}`;
                    } else {
                        media.addEventListener('load', function() {
                            const height = media.offsetHeight;
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
