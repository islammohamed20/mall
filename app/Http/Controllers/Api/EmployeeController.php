<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::orderBy('name', 'asc')->get();
        return response()->json([
            'data' => $employees,
            'message' => 'Employees retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'sometimes|uuid',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'startDate' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['startDate'])) {
            $validated['start_date'] = $validated['startDate'];
            unset($validated['startDate']);
        }

        $employee = Employee::create($validated);

        return response()->json([
            'data' => $employee,
            'message' => 'Employee created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json([
            'data' => $employee,
            'message' => 'Employee retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'startDate' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['startDate'])) {
            $validated['start_date'] = $validated['startDate'];
            unset($validated['startDate']);
        }

        $employee->update($validated);

        return response()->json([
            'data' => $employee,
            'message' => 'Employee updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }
}
