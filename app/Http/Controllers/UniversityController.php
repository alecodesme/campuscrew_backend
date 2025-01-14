<?php

namespace App\Http\Controllers;

use App\Mail\AcceptUniversityMail;
use App\Mail\RejectUniversityMail;
use App\Models\University;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UniversityController extends Controller
{


    public function index()
    {
        $universities = University::all();
        return response()->json([
            'universities' => $universities
        ]);
    }



    public function getClubs($universityId)
    {
        $university = University::findOrFail($universityId);

        if (!$university) {
            return response()->json([
                'error' => 'University not found'
            ], 404);
        }

        return response()->json([
            'clubs' => $university->clubs
        ]);
    }

    public function getStudents($universityId)
    {
        $university = University::findOrFail($universityId);

        if (!$university) {
            return response()->json([
                'error' => 'University not found'
            ], 404);
        }

        return response()->json([
            'students' => $university->students
        ]);
    }

    public function getMyUniversities(Request $request)
    {
        $user = $request->user();

        $universities = University::where('user_id', $user->id)
            ->with('clubs')
            ->get();

        return response()->json([
            'universities' => $universities
        ]);
    }

    public function getUniversity($universityId)
    {
        $university = University::findOrFail($universityId)
            ->with('clubs')
            ->get();

        return response()->json([
            'data' => $university
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'email' => 'required|unique:universities,email|email',
                'cellphone' => 'required|string|max:20',
                'domain' => 'required|string|unique:universities,domain',
            ]);

            $university = University::create($validated);

            return response()->json([
                'message' => 'University created successfully.',
                'university' => $university
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the university.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $university = University::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'address' => 'sometimes|string|max:255',
                'country' => 'sometimes|string|max:255',
                'city' => 'sometimes|string|max:255',
                'email' => 'required|unique:universities,email,' . $id . '|email',
                'province' => 'sometimes|string|max:255',
                'cellphone' => 'sometimes|string|max:20',
                'user_id' => 'sometimes|exists:users,id',
                'domain' => 'sometimes|string|unique:universities,domain,' . $id,
            ]);

            $university->update($validated);

            return response()->json([
                'message' => 'University updated successfully.',
                'university' => $university,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'University not found.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the university.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function statusUniversity(Request $request, $id)
    {
        try {

            $university = University::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:pending,accepted,rejected',
            ]);
            if ($validated['status'] == 'accepted' && $university->user_id == null) {
                return $this->handleAcceptedStatus($university, $validated['status']);
            } else if ($validated['status'] == 'rejected' && $university->user_id == null) {
                return $this->handleRejectedStatus($university, $validated['status'], $request['observation']);
            } else {
                return $this->handleOtherStatuses($university, $validated['status']);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'University not found.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    private function handleAcceptedStatus($university, $status)
    {
        try {
            $userCreated = false;
            $password = null;

            DB::beginTransaction();

            if ($university->user_id == null) {
                $password = 'password';

                $user = User::create([
                    'name' => $university->name,
                    'email' => $university->email,
                    'password' => bcrypt($password),
                    'role' => 'university',
                ]);

                $university->user_id = $user->id;
                $userCreated = true;
            }

            $university->status = $status;

            $university->save();

            $mailData = [
                'email' => $user->email,
                'password' => $password,
                'university' => $university->name,
            ];


            $this->sendAcceptanceEmail($user->email, $mailData);

            DB::commit();

            return response()->json([
                'message' => 'University updated successfully.',
                'university' => $university,
                'user_created' => $userCreated,
                'generated_password' => $password,
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Database error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    private function handleRejectedStatus($university, $status, $observation)
    {
        DB::beginTransaction();

        try {
            $university->status = $status;

            $university->save();

            $mailData = [
                'email' => $university->email,
                'observation' => $observation,
                'university' => $university->name,
            ];


            $this->sendRejectedEmail($university->email, $mailData);

            DB::commit();

            return response()->json([
                'message' => 'University rejected successfully.',
                'university' => $university,
                'user_created' => false,
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Database error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    private function sendAcceptanceEmail($email, $mailData)
    {
        try {
            Mail::to($email)->send(new AcceptUniversityMail($mailData));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email couldnt sended',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function sendRejectedEmail($email, $mailData)
    {
        try {
            Mail::to($email)->send(new RejectUniversityMail($mailData));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email couldnt sended',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }


    private function handleOtherStatuses($university, $status)
    {
        $university->status = $status;
        $university->save();

        return response()->json([
            'message' => 'University updated successfully.',
            'user_created' => false,
            'university' => $university,
        ]);
    }



    public function destroy($id)
    {
        try {
            $university = University::findOrFail($id);

            $university->delete();

            return response()->json([
                'message' => 'University deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'University not found.',
            ], 404);
        } catch (Exception $e) {
            var_dump($e);
            return response()->json([
                'message' => 'An error occurred while deleting the university.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
