<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Expense::orderBy('date', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|uuid',
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'invoiceImage' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $expense = Expense::create($validated);
        ActivityLog::record("إضافة مصروف جديد", $expense->description, "expense");
        return response()->json(['data' => $expense], 201);
    }

    public function show(Expense $expense)
    {
        return response()->json(['data' => $expense]);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'sometimes|string',
            'description' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'invoiceImage' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $expense->update($validated);
        ActivityLog::record("تعديل مصروف", $expense->description, "expense");
        return response()->json(['data' => $expense]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        ActivityLog::record("حذف مصروف", $expense->description, "expense");
        return response()->json(['message' => 'Deleted']);
    }
}
