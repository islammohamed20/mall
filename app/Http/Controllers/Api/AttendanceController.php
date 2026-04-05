<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        if ($request->has('month')) {
            $query->where('date', 'like', $request->month . '%');
        }

        $items = $query->with('employee')->get();

        return response()->json([
            'data' => $items,
            'message' => 'Attendance records retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave,late',
            'check_in' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $item = Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            $validated
        );

        $statusLabel = [
            'present' => 'حاضر',
            'absent' => 'غائب',
            'leave' => 'إجازة',
            'late' => 'متأخر',
        ][$item->status] ?? $item->status;

        $empName = $item->employee ? $item->employee->name : "موظف";
        ActivityLog::record("تسجيل {$statusLabel}", "للموظف {$empName} بتاريخ {$item->date->format('Y-m-d')}", "attendance");

        return response()->json([
            'data' => $item,
            'message' => 'تم تسجيل الحضور بنجاح'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return response()->json([
            'data' => $attendance->load('employee')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:present,absent,leave,late',
            'check_in' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return response()->json([
            'data' => $attendance,
            'message' => 'تم تحديث السجل بنجاح'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'message' => 'تم حذف السجل بنجاح'
        ]);
    }
}
