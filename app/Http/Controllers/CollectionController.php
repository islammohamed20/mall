<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Collection::orderBy('date', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        \Log::info('Collection Store Request:', [
            'has_image' => $request->has('receiptImage'),
            'image_len' => strlen($request->input('receiptImage', '')),
        ]);
        $validated = $request->validate([
            'id' => 'nullable|uuid',
            'shopId' => 'nullable|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:rent,other',
            'receiptImage' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $collection = Collection::create($validated);
        $typeLabel = $collection->type === 'rent' ? 'إيجار' : 'أخرى';
        ActivityLog::record("تحصيل مبلغ ({$typeLabel})", "بمبلغ {$collection->amount} ج.م", "collection");
        return response()->json(['data' => $collection], 201);
    }

    public function show(Collection $collection)
    {
        return response()->json(['data' => $collection]);
    }

    public function update(Request $request, Collection $collection)
    {
        \Log::info('Collection Update Request:', [
            'has_image' => $request->has('receiptImage'),
            'image_len' => strlen($request->input('receiptImage', '')),
        ]);
        $validated = $request->validate([
            'shopId'       => 'sometimes|nullable|string',
            'amount'       => 'sometimes|numeric',
            'date'         => 'sometimes|date',
            'type'         => 'sometimes|in:rent,other',
            'receiptImage' => 'sometimes|nullable|string',
            'notes'        => 'sometimes|nullable|string',
        ]);
        $collection->update($validated);
        $typeLabel = $collection->type === 'rent' ? 'إيجار' : 'أخرى';
        ActivityLog::record("تعديل تحصيل ({$typeLabel})", "بمبلغ {$collection->amount} ج.م", "collection");
        return response()->json(['data' => $collection->fresh()]);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        $typeLabel = $collection->type === 'rent' ? 'إيجار' : 'أخرى';
        ActivityLog::record("حذف تحصيل ({$typeLabel})", "بمبلغ {$collection->amount} ج.م", "collection");
        return response()->json(['message' => 'Deleted']);
    }
}
