<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];
        $baseUrl = config('app.url');

        // Static Pages
        $urls[] = ['loc' => $baseUrl . '/', 'priority' => '1.0', 'lastmod' => now()->toAtomString()];
        $urls[] = ['loc' => $baseUrl . '/about', 'priority' => '0.8', 'lastmod' => now()->toAtomString()];
        $urls[] = ['loc' => $baseUrl . '/contact', 'priority' => '0.8', 'lastmod' => now()->toAtomString()];
        $urls[] = ['loc' => $baseUrl . '/shops', 'priority' => '0.9', 'lastmod' => now()->toAtomString()];
        $urls[] = ['loc' => $baseUrl . '/offers', 'priority' => '0.9', 'lastmod' => now()->toAtomString()];
        $urls[] = ['loc' => $baseUrl . '/events', 'priority' => '0.9', 'lastmod' => now()->toAtomString()];

        // Dynamic Shops
        $shops = Shop::where('is_active', true)->get();
        foreach ($shops as $shop) {
            $urls[] = [
                'loc' => route('shops.show', $shop->slug),
                'priority' => '0.8',
                'lastmod' => $shop->updated_at->toAtomString()
            ];
        }

        // Dynamic Offers
        $offers = Offer::all();
        foreach ($offers as $offer) {
            $urls[] = [
                'loc' => route('offers.show', $offer->slug),
                'priority' => '0.7',
                'lastmod' => $offer->updated_at->toAtomString()
            ];
        }

        // Dynamic Events
        $events = Event::all();
        foreach ($events as $event) {
            $urls[] = [
                'loc' => route('events.show', $event->slug),
                'priority' => '0.7',
                'lastmod' => $event->updated_at->toAtomString()
            ];
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }
}
