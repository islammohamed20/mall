<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $offersQuery = Offer::query()
            ->active()
            ->with('shop')
            ->ordered();

        if ($request->boolean('upcoming')) {
            $offersQuery->upcoming();
        } else {
            $offersQuery->current();
        }

        $offers = $offersQuery->paginate(12)->withQueryString();

        return view('pages.offers.index', compact('offers'));
    }

    public function show(Offer $offer)
    {
        if (! $offer->is_active) {
            abort(404);
        }

        $offer->load('shop');

        return view('pages.offers.show', compact('offer'));
    }
}
