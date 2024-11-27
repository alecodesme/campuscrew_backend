<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        // // Obtén el usuario autenticado
        $user = $request->user();

        // Obtener todas las universidades asociadas al usuario autenticado
        $universities = University::where('user_id', $user->id)->get();

        return response()->json([
            'universities' => $universities
        ]);

    }
    // Crear universidad
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'cellphone' => 'required|string|max:20',
                'user_id' => 'required|exists:users,id',
                'domain' => 'required|string|unique:universities,domain',
            ]);

            // Crear la universidad
            $university = University::create($validated);

            return response()->json([
                'message' => 'University created successfully.',
                'university' => $university
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Capturar cualquier otro error
            return response()->json([
                'message' => 'An error occurred while creating the university.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Editar universidad
    public function update(Request $request, $id)
    {
        try {
            // Buscar la universidad por ID
            $university = University::findOrFail($id);
    
            // Validar los datos enviados
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'address' => 'sometimes|string|max:255',
                'country' => 'sometimes|string|max:255',
                'city' => 'sometimes|string|max:255',
                'province' => 'sometimes|string|max:255',
                'cellphone' => 'sometimes|string|max:20',
                'user_id' => 'sometimes|exists:users,id',
                'domain' => 'sometimes|string|unique:universities,domain,' . $id,
            ]);
    
            // Actualizar la universidad con los datos validados
            $university->update($validated);
    
            // Responder con éxito
            return response()->json([
                'message' => 'University updated successfully.',
                'university' => $university,
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si no se encuentra la universidad
            return response()->json([
                'message' => 'University not found.',
            ], 404);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            // Capturar cualquier otro error inesperado
            return response()->json([
                'message' => 'An error occurred while updating the university.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar universidad (Soft Delete)
    public function destroy($id)
    {
        try {
            // Buscar la universidad por ID
            $university = University::findOrFail($id);

            // Realizar soft delete
            $university->delete();

            return response()->json([
                'message' => 'University deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si no se encuentra la universidad
            return response()->json([
                'message' => 'University not found.',
            ], 404);
        } catch (\Exception $e) {
            var_dump($e);
            // Capturar cualquier otro error
            return response()->json([
                'message' => 'An error occurred while deleting the university.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
