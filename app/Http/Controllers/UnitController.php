<?php

namespace App\Http\Controllers;

use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::active()
            ->with('floor')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        return view('pages.units.index', compact('units'));
    }

    public function show(Unit $unit)
    {
        abort_unless($unit->is_active, 404);
        $unit->load('floor');

        $relatedUnits = Unit::active()
            ->where('id', '!=', $unit->id)
            ->where('type', $unit->type)
            ->limit(4)
            ->get();

        return view('pages.units.show', compact('unit', 'relatedUnits'));
    }
}
