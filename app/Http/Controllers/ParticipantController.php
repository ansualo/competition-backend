<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends Controller
{
    public function register(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email:rfc,dns',
                'date_of_birth' => 'required|date|before:-18 years'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $validData = $validator->validated();

            $participant = Participant::create([
                'name' => $validData['name'],
                'email' => $validData['email'],
                'date_of_birth' => $validData['date_of_birth']
            ]);

            return response()->json([
                'message' => 'Participant registered successfully',
                'data' => $participant
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error registering participant' . $th->getMessage());

            return response()->json([
                'message' => 'Error registering user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getParticipants()
    {
        try {

            $participants = Participant::get();

            return response()->json([
                'message' => 'Participants retrieved',
                'data' => $participants
            ], Response::HTTP_OK);
            
        } catch (\Throwable $th) {
            Log::error('Error retrieving participants' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving participants',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
