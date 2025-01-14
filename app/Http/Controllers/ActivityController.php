<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Exception;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_event' => 'required|boolean',
            'club_id' => 'integer'
        ]);

        try {
            $student = Activity::create($validated);

            return response()->json([
                'message' => 'Activity created successfully.',
                'student' => $student,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error creating Activity: ' . $e->getMessage(),
            ], 500);
        }
    }
}
