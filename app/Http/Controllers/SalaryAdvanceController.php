<?php

namespace App\Http\Controllers;

use App\Models\SalaryAdvance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SalaryAdvanceController extends Controller
{
    public function index()
    {
        return response()->json(['data' => SalaryAdvance::orderBy('date', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|uuid',
            'employeeId' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $salaryAdvance = SalaryAdvance::create($validated);
        ActivityLog::record("صرف سلفة جديدة", "بمبلغ {$salaryAdvance->amount} ج.م", "salary");
        return response()->json(['data' => $salaryAdvance], 201);
    }

    public function show(SalaryAdvance $salaryAdvance)
    {
        return response()->json(['data' => $salaryAdvance]);
    }

    public function update(Request $request, SalaryAdvance $salaryAdvance)
    {
        $validated = $request->validate([
            'employeeId' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);
        $salaryAdvance->update($validated);
        ActivityLog::record("تعديل سلفة", "بمبلغ {$salaryAdvance->amount} ج.م", "salary");
        return response()->json(['data' => $salaryAdvance]);
    }

    public function destroy(SalaryAdvance $salaryAdvance)
    {
        $salaryAdvance->delete();
        ActivityLog::record("حذف سلفة", "بمبلغ {$salaryAdvance->amount} ج.م", "salary");
        return response()->json(['message' => 'Deleted']);
    }
}
