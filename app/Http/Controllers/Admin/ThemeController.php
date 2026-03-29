<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HijriDateService;
use App\Services\SeasonThemeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index(SeasonThemeService $themes, HijriDateService $hijri)
    {
        $items = $themes->adminList($hijri);
        $activeKey = $themes->activeKey();
        $categories = $themes->categories();

        // Group items by category
        $groupedItems = [];
        foreach ($items as $item) {
            $cat = $item['category'] ?? 'gregorian';
            $groupedItems[$cat][] = $item;
        }

        $hijriToday = $hijri->todayHijri();
        $hijriSupported = $hijri->isSupported();

        return view('admin.themes.index', compact('groupedItems', 'categories', 'activeKey', 'hijriToday', 'hijriSupported'));
    }

    public function activate(Request $request, SeasonThemeService $themes): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $key = (string) $data['key'];

        if (! $themes->theme($key)) {
            return back()->with('error', app()->getLocale() === 'ar' ? 'ثيم غير صحيح.' : 'Invalid theme.');
        }

        $themes->setActiveKey($key);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم تفعيل الثيم.' : 'Theme activated.');
    }

    public function deactivate(SeasonThemeService $themes): RedirectResponse
    {
        $themes->setActiveKey('');

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم إيقاف الثيم.' : 'Theme deactivated.');
    }
}
