<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => "false",
                "message" => $validator->errors()->first()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $user->assignRole('customer');

        return response()->json([
            'success' => true,
            'user' => $user,
        ],201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => "false",
                "message" => $validator->errors()->first()
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken("apiToken")->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => "Login successful",
            'token' => $token,
        ]);
    }

    public function logout(){
        Auth::user()->tokens()->delete();

        return [
            'success' => true,
            'message' => 'User logged out',
        ];
    }
}
