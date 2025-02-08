<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        $employees = $query->paginate(10);
        return response()->json($employees);
    }

    public function show($id)
    {
        $employee = Employee::with('timesheets')->findOrFail($id);
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'job_title'     => 'nullable|string|max:255',
            'department'    => 'nullable|string|max:255',
            'salary'        => 'nullable|numeric',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'photo'         => 'nullable|image|max:2048',
            'documents.*'   => 'nullable|file|max:4096', 
            'identities.*'   => 'nullable|file|max:4096', 

        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
        }

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $file) {
                $documents[] = $file->store('employees/documents', 'public');
            }
            $validatedData['documents'] = $documents;
        }

        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $file) {
                $documents[] = $file->store('employees/identities', 'public');
            }
            $validatedData['identities'] = $documents;
        }

        $employee = Employee::create($validatedData);

        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email,'.$employee->id,
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'job_title'     => 'nullable|string|max:255',
            'department'    => 'nullable|string|max:255',
            'salary'        => 'nullable|numeric',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'photo'         => 'nullable|image|max:2048',
            'documents.*'   => 'nullable|file|max:4096',
            'identities.*'   => 'nullable|file|max:4096', 

        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')
                ->store('employees/photos', 'public');
        }

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $file) {
                $documents[] = $file->store('employees/documents', 'public');
            }
            $validatedData['documents'] = $documents;
        }

        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $file) {
                $documents[] = $file->store('employees/identities', 'public');
            }
            $validatedData['identities'] = $documents;
        }
        
        $employee->update($validatedData);
        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(null, 204);
    }
}
