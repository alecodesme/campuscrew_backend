<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'university_id' => 'integer'
        ]);

        try {
            $student = Student::create($validated);

            return response()->json([
                'message' => 'Student created successfully.',
                'student' => $student,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error creating student: ' . $e->getMessage(),
            ], 500);
        }
    }
}
