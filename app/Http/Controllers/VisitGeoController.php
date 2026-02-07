<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitGeoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy_m' => ['nullable', 'numeric', 'min:0', 'max:100000'],
        ]);

        $geo = [
            'lat' => (float) $data['lat'],
            'lng' => (float) $data['lng'],
            'accuracy_m' => isset($data['accuracy_m']) ? (float) $data['accuracy_m'] : null,
            'source' => 'browser',
            'captured_at' => now(),
        ];

        $request->session()->put('geo', $geo);

        try {
            $visit = Visit::query()
                ->where('session_id', $request->session()->getId())
                ->latest('id')
                ->first();

            if ($visit) {
                $visit->update([
                    'lat' => $geo['lat'],
                    'lng' => $geo['lng'],
                    'accuracy_m' => $geo['accuracy_m'],
                    'geo_source' => $geo['source'],
                    'geo_captured_at' => $geo['captured_at'],
                ]);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return response()->noContent();
    }
}
