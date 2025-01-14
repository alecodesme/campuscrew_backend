<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    // Crear un club
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'university_id' => 'required|exists:universities,id',
            'email' => 'nullable|email',
            'is_active' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        try {
            $club = Club::create($validated);

            return response()->json([
                'message' => 'Club created successfully.',
                'club' => $club,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating club: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Obtener todos los clubes
    public function index()
    {
        $clubs = Club::all();
        return response()->json([
            'clubs' => $clubs,
        ]);
    }

    public function show($id)
    {
        $club = Club::find($id);

        if (!$club) {
            return response()->json([
                'message' => 'Club not found.',
            ], 404);
        }

        return response()->json([
            'club' => $club,
        ]);
    }

    // Editar un club
    public function update(Request $request, $id)
    {
        $club = Club::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'university_id' => 'sometimes|exists:universities,id',
            'created_by' => 'sometimes|exists:users,id',
            'email' => 'nullable|email',
            'is_active' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        try {
            $club->update($validated);

            return response()->json([
                'message' => 'Club updated successfully.',
                'club' => $club,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating club: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar (soft delete) un club
    public function destroy($id)
    {
        try {
            $club = Club::findOrFail($id);
            $club->delete();

            return response()->json([
                'message' => 'Club deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error deleting club: ' . $e->getMessage(),
            ], 500);
        }
    }
}
