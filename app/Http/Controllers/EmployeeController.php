<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\EmployeeFileProcessor;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * Import employees from a CSV file
     */
    public function import(Request $request)
    {
        // Support both methods : binary and multipart/form-data uploads
        if(isset($request->file) && $request->file->isValid()) {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt'
            ]);
            $file = $request->file('file');
            $file = file($file->getRealPath());
        } else {
            $file = $request->getContent();
            $file = explode(PHP_EOL, $file);
            // Remove last item because it's empty in binary imports
            array_pop($file);
        }

        $processor = new EmployeeFileProcessor($file);

        if(!$processor->validate()) {
            return response()->json([
                'message' => 'The CSV files contained errors and was not processed',
                'errors' => $processor->getErrors(),
            ], 422);
        }

        $processor->prepare();
        $processor->insert();

        return response()->json([
            'message' => 'Import started. This will take some time.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully'], 204);
    }
}
