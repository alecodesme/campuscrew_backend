<?php

namespace App\Http\Controllers;

use App\Exceptions\UniversityNotAcceptedException;
use App\Models\University;
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
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $validated['email'])->first();


            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['The provided credentials are incorrect.'],
                ]);
            }

            $universityProfile = $this->getUniversityProfile($user);

            if ($universityProfile->status !== 'accepted') {
                throw new UniversityNotAcceptedException();
            }

            $token = JWTAuth::attempt($validated);

            if (!$token) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);



            if ($user->role == 'admin') {
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'university' => $universityProfile,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function me()
    {
        $user = Auth::user();
        $universityProfile = $this->getUniversityProfile($user);
        if ($user->role == 'admin') {
            return response()->json([
                'message' => 'User admin retrieved succesfully',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User university retrieved succesfully',
                'user' => $user,
                'university' => $universityProfile,
            ], 200);
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            JWTAuth::invalidate($token);

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Successfully logged out', 'status' => true], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out', 'status' => false], 500);
        }
    }

    private function getUniversityProfile(User $user)
    {
        $universityProfile = null;

        if ($user->role == 'university') {
            $universityProfile = University::where('user_id', $user->id)->first();

            // Instead of returning response, just return null or false if not found
            if (!$universityProfile) {
                return null;  // Return null if the profile is not found
            }
        }

        return $universityProfile;
    }
}
