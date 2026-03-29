<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfferRequest;
use App\Http\Requests\Admin\UpdateOfferRequest;
use App\Models\Offer;
use App\Models\Shop;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::query()
            ->with('shop')
            ->latest()
            ->paginate(15);

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $shops = Shop::query()->ordered()->get();

        return view('admin.offers.create', compact('shops'));
    }

    public function store(StoreOfferRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title_en']);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('offers', 'public');
        }

        Offer::create($data);

        return redirect()->route('admin.offers.index')->with('status', 'Saved.');
    }

    public function edit(Offer $offer)
    {
        $shops = Shop::query()->ordered()->get();

        return view('admin.offers.edit', compact('offer', 'shops'));
    }

    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title_en']);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('offers', 'public');
        }

        $offer->update($data);

        return redirect()->route('admin.offers.index')->with('status', 'Saved.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('status', 'Deleted.');
    }
}
