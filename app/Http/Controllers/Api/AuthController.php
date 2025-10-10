<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    // public function login(Request $request)
    // {
    //     $validation = Validator::make($request->all(), [
    //         'name'          => 'required|string',
    //         'password'      => 'required|string',
    //         'serial_number' => 'required|string',
    //     ]);

    //     if ($validation->fails()) {
    //         return response()->json([
    //             'status'  => false,
    //             'error'   => $validation->errors(),
    //             'message' => 'Validation error',
    //         ], 422);
    //     }

    //     // Check machine first
    //     $machine = PosMachine::where('serial_number', $request->serial_number)
    //         ->where('status', 1)
    //         ->first();

    //     if (! $machine) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Invalid or inactive POS machine. Contact administrator.',
    //         ], 403);
    //     }

    //     // Try login via pos_users table
    //     $posUser = DB::table('pos_users')
    //         ->where('name', $request->name)
    //         ->where('company_id', $machine->company_id)
    //         ->first();

    //     if ($posUser) {

    //         if (! Hash::check($request->password, $posUser->password)) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Invalid POS username or password',
    //             ], 401);
    //         }

    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'POS User login successful',
    //             'user'    => [
    //                 'name'          => $posUser->name,
    //                 'company_id'    => $machine->company_id,
    //                 'serial_number' => $machine->serial_number,
    //                 'position'      => $posUser->position ?? null,
    //                 'type'          => 'POS User',
    //             ],
    //         ], 200);
    //     }

    //     // Otherwise try login via main users table
    //     $user = User::where('name', $request->name)->first();

    //     if (! $user) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'User not found',
    //         ], 404);
    //     }

    //     // Only Company-admin allowed for main users table
    //     if (! $user->hasRole('Company-admin')) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Only Company-admin can login',
    //         ], 403);
    //     }

    //     // Check password for main user (hashed)
    //     if (! Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Invalid username or password',
    //         ], 401);
    //     }

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Company-admin login successful',
    //         'user'    => [
    //             'name'          => $user->name,
    //             'email'         => $user->email,
    //             'company_id'    => $machine->company_id,
    //             'serial_number' => $machine->serial_number,
    //             'position'      => 'Admin',
    //             'type'          => 'Company-admin',
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


    $machine = PosMachine::where('serial_number', $request->serial_number)
        ->where('status', 1)
        ->first();

    if (! $machine) {
        return response()->json([
            'status'  => false,
            'message' => 'Invalid or inactive POS machine. Contact administrator.',
        ], 403);
    }

    $posUser = DB::table('pos_users')
        ->where('name', $request->name)
        ->where('company_id', $machine->company_id)
        ->first();

    if ($posUser) {
        $storedPassword = $posUser->password;
        $inputPassword  = $request->password;

        $isValid = false;

        // Case 1: bcrypt/argon hashed password
        if (Str::startsWith($storedPassword, ['$2y$', '$2a$', '$argon2'])) {
            $isValid = Hash::check($inputPassword, $storedPassword);
        } else {
            // Case 2: maybe plain or Crypt::encryptString()
            try {
                $decrypted = Crypt::decryptString($storedPassword);
            } catch (\Exception $e) {
                $decrypted = $storedPassword; // if decrypt fails, assume plain text
            }

            $isValid = hash_equals($decrypted, $inputPassword);
        }

        if (! $isValid) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid POS username or password',
            ], 401);
        }

        // ✅ Success
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

    // ✅ Check Company Admin (same as before)
    $user = User::where('name', $request->name)->first();

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

public function masterlogin(Request $request)
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

    $machine = PosMachine::where('serial_number', $request->serial_number)
        ->where('status', 1)
        ->first();

    if (! $machine) {
        return response()->json([
            'status'  => false,
            'message' => 'Invalid or inactive POS machine. Contact administrator.',
        ], 403);
    }

    // 🔹 First check if POS User trying to login
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
                'id'            => $posUser->id,
                'name'          => $posUser->name,
                'company_id'    => $machine->company_id,
                'serial_number' => $machine->serial_number,
                'position'      => $posUser->position ?? null,
                'type'          => 'POS User',
            ],
        ], 200);
    }

    // 🔹 Now check if Company-admin
    $user = User::where('name', $request->name)->first();

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

    if (! Hash::check($request->password, $user->password)) {
        return response()->json([
            'status'  => false,
            'message' => 'Invalid username or password',
        ], 401);
    }

    // 🔹 Fetch all POS Users of same company (excluding admin)
    $posUsers = DB::table('pos_users')
        ->where('company_id', $machine->company_id)
        ->select('id', 'name', 'position', 'password')
        ->get();

    // ✅ Admin login success
    return response()->json([
        'status'    => true,
        'message'   => 'Admin login successful',
        'user'      => [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'company_id'    => $machine->company_id,
            'serial_number' => $machine->serial_number,
            'position'      => 'Admin',
            'password'      => $user->password, // 🔹 show hashed password here only
        ],
        'pos_users' => $posUsers, // 🔹 admin excluded here
    ], 200);
}



}
