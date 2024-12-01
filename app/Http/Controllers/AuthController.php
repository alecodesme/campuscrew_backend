<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validación de los datos de la solicitud
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,university',
            ]);

            // Creación del nuevo usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Respuesta exitosa
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            // Capturar cualquier error y devolver una respuesta con el mensaje de error
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage(),
            ], 500); // Puedes cambiar el código de estado según el tipo de error
        }
    }



    public function login(Request $request)
    {


        try {
            // Validación de los datos de entrada
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Busca el usuario por email
            $user = User::where('email', $validated['email'])->first();

            // Verifica si el usuario existe y la contraseña es válida
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Genera el token de autenticación (JWT)
            $token = JWTAuth::attempt($validated);

            // Si no se pudo generar el token, devuelve error
            if (!$token) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // (Opcional) Se puede agregar el rol al token
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            // Devuelve errores de validación
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (JWTException $e) {
            // Error al generar el token JWT
            return response()->json(['error' => 'Could not create token'], 500);
        } catch (Exception $e) {
            // Manejo de otros errores inesperados
            return response()->json([
                'message' => 'An unexpected error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function me()
    {
        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            JWTAuth::invalidate($token);

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }
}
