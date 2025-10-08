<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    //         'user'    => [
    //             'name'  => $user->name,
    //             'email' => $user->email,
    //         ],
    //     ], 200);
    // }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'          => 'required|string',
            'password'      => 'required|string',
            'serial_number' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status'  => false,
                'error'   => $validation->errors(),
                'message' => 'Validation error',
            ], 422);
        }

        // Check machine first
        $machine = PosMachine::where('serial_number', $request->serial_number)
            ->where('status', 1)
            ->first();

        if (! $machine) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or inactive POS machine. Contact administrator.',
            ], 403);
        }

        // Try login via pos_users table
        $posUser = DB::table('pos_users')
            ->where('name', $request->name)
            ->where('company_id', $machine->company_id)
            ->first();

        if ($posUser) {

            if (! Hash::check($request->password, $posUser->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid POS username or password',
                ], 401);
            }

            return response()->json([
                'status'  => true,
                'message' => 'POS User login successful',
                'user'    => [
                    'name'          => $posUser->name,
                    'company_id'    => $machine->company_id,
                    'serial_number' => $machine->serial_number,
                    'position'      => $posUser->position ?? null,
                    'type'          => 'POS User',
                ],
            ], 200);
        }

        // Otherwise try login via main users table
        $user = User::where('name', $request->name)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        // Only Company-admin allowed for main users table
        if (! $user->hasRole('Company-admin')) {
            return response()->json([
                'status'  => false,
                'message' => 'Only Company-admin can login',
            ], 403);
        }

        // Check password for main user (hashed)
        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid username or password',
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Company-admin login successful',
            'user'    => [
                'name'          => $user->name,
                'email'         => $user->email,
                'company_id'    => $machine->company_id,
                'serial_number' => $machine->serial_number,
                'position'      => 'Admin',
                'type'          => 'Company-admin',
            ],
        ], 200);
    }

}
