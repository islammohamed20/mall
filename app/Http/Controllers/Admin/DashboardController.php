<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Shop;
use App\Models\ShopCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'shops' => Shop::query()->count(),
            'categories' => ShopCategory::query()->count(),
            'offers' => Offer::query()->count(),
            'events' => Event::query()->count(),
            'messages' => ContactMessage::query()->count(),
        ];

        $latestMessages = ContactMessage::query()
            ->latest()
            ->take(8)
            ->get();

        $upcomingEvents = Event::query()
            ->active()
            ->upcoming()
            ->ordered()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestMessages', 'upcomingEvents'));
    }
}
