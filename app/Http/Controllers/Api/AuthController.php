<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    // public function login(Request $request)
    // {
    //     $validation = Validator::make($request->all(), [
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if ($validation->fails()) {
    //         return response()->json([
    //             'status'  => false,
    //             'error'   => $validation->errors(),
    //             'message' => 'Validation error',
    //         ], 422);
    //     }
    //     $user = User::where('email', $request->email)->first();

    //     if (! $user) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'User not found',
    //         ], 404);
    //     }
    //     if (! $user->hasRole('Company-admin')) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Only Company-admin can login',
    //         ], 403);
    //     }
    //     if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Invalid email or password',
    //         ], 401);
    //     }

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Login successful',
    //         'user'    => $user,
    //     ], 200);
    // }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status'  => false,
                'error'   => $validation->errors(),
                'message' => 'Validation error',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        if (! $user->hasRole('Company-admin')) {
            return response()->json([
                'status'  => false,
                'message' => 'Only Company-admin can login',
            ], 403);
        }

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }
}
