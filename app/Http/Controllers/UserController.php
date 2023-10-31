<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "User not found",
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function show()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            throw new NotFoundHttpException('User not found');
        }
        return response()->json([
            "status" => "success",
            "users" => $users
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'string|email|required|unique:users',
                'password' => 'required|string|confirmed',
                'role' => 'required|string|in:admin,vendor,customer',
                'active' => 'integer|in:1,0'
            ],
            [
                'role.in' => 'Invalid role, expects admin, vendor, or customer',
                'active.in' => 'Invalid active state, expects 1 or 0'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422); // Unprocessable Entity
        }

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash the password securely
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->role) . ' created successfully',
        ], 200);
    }

    public function activateOrDeactivate(Request $request, $id)
    {
        $userResponse = $this->getUserById($id);
        if (!$userResponse->original['success']) {
            return $userResponse;
        }
        $user = $userResponse->original['data'];

        if (!$request->has('active')) {
            return response()->json([
                'success' => false,
                'message' => 'An active state of 1 or 0 is required'
            ], 400); // Bad Request
        }

        $validator = Validator::make(
            $request->all(),
            [
                'active' => 'integer|in:0,1',
            ],
            [
                'active.in' => 'Invalid active state, expects 1 or 0'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422); // Unprocessable Entity
        }

        $active = $request->input('active');

        $user->active = $active;
        $user->save();

        $message = $active ? 'activated successfully' : 'deactivated successfully';

        return response()->json([
            'success' => true,
            'message' => $user->email . ' ' . $message,
        ], 200);
    }

    public function delete($id)
    {
        $user = $this->getUser($id);

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => $user->email . ' deleted successfully',
        ], 200);
    }
}
