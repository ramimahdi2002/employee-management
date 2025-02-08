<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with('employee');

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $timesheets = $query->paginate(10);
        return response()->json($timesheets);
    }

    public function show($id)
    {
        $timesheet = Timesheet::with('employee')->findOrFail($id);
        return response()->json($timesheet);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'summary'     => 'nullable|string',
        ]);
        
        $timesheet = Timesheet::create($validatedData);
        return response()->json($timesheet, 201);
    }

    public function update(Request $request, $id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'summary'     => 'nullable|string',
        ]);

        $timesheet->update($validatedData);
        return response()->json($timesheet);
    }

    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();
        return response()->json(null, 204);
    }
}
