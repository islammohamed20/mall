<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Facility;
use App\Models\Offer;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->active()
            ->current()
            ->ordered()
            ->take(5)
            ->get();

        $categories = ShopCategory::query()
            ->active()
            ->ordered()
            ->take(8)
            ->get();

        $featuredShops = Shop::query()
            ->active()
            ->featured()
            ->with(['category', 'floorRelation'])
            ->ordered()
            ->take(8)
            ->get();

        $currentOffers = Offer::query()
            ->active()
            ->current()
            ->with('shop')
            ->ordered()
            ->take(6)
            ->get();

        $currentEvents = Event::query()
            ->active()
            ->current()
            ->with('shop')
            ->ordered()
            ->take(6)
            ->get();

        $facilities = Facility::query()
            ->active()
            ->ordered()
            ->take(8)
            ->get();

        return view('pages.home', compact(
            'sliders',
            'categories',
            'featuredShops',
            'currentOffers',
            'currentEvents',
            'facilities',
        ));
    }
}
