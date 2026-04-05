<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashBox;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CashBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = CashBox::orderBy('date', 'desc')->get();
        return response()->json([
            'data' => $items,
            'message' => 'CashBox items retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|uuid',
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $item = CashBox::create($validated);

        $typeLabel = $item->type === 'in' ? 'وارد إلى' : 'صادر من';
        ActivityLog::record("إضافة حركة {$typeLabel} الخزنة", "بمبلغ {$item->amount} ج.م - {$item->description}", "cashbox");

        return response()->json([
            'data' => $item,
            'message' => 'تم إضافة الحركة بنجاح'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CashBox $cashbox)
    {
        return response()->json([
            'data' => $cashbox
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashBox $cashbox)
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|in:in,out',
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'notes' => 'nullable|string',
        ]);

        $cashbox->update($validated);

        $typeLabel = $cashbox->type === 'in' ? 'وارد إلى' : 'صادر من';
        ActivityLog::record("تحديث حركة في الخزنة", "بمبلغ {$cashbox->amount} ج.م - {$cashbox->description}", "cashbox");

        return response()->json([
            'data' => $cashbox,
            'message' => 'تم تحديث الحركة بنجاح'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashBox $cashbox)
    {
        $cashbox->delete();

        $typeLabel = $cashbox->type === 'in' ? 'وارد إلى' : 'صادر من';
        ActivityLog::record("حذف حركة من الخزنة", "بمبلغ {$cashbox->amount} ج.م - {$cashbox->description}", "cashbox");

        return response()->json([
            'message' => 'تم مسح الحركة بنجاح'
        ]);
    }
}
