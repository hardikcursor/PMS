<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
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
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username'      => 'required|string',
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

        $machine = PosMachine::where('serial_number', $request->serial_number)->first();

        if (! $machine) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid serial number. Please check and try again.',
            ], 404);
        }

        if ($machine->status != 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Your POS machine is inactive. Please contact administrator.',
            ], 403);
        }

        $license = License::where('user_id', $machine->company_id)->first();

        $posUser = DB::table('users')
            ->where('name', $request->username)
            ->where('pos_machine_id', $machine->id)
            ->first();

        if ($posUser) {
            if ($posUser->company_id != $machine->company_id) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This POS user does not belong to the selected company.',
                ], 403);
            }

            if (! $license || now()->gt($license->license_validity)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Your company license has expired. Please contact administrator.',
                ], 403);
            }

            $storedPassword = $posUser->password;
            $inputPassword  = $request->password;
            $isValid        = false;

            if (Str::startsWith($storedPassword, ['$2y$', '$2a$', '$argon2'])) {
                $isValid = Hash::check($inputPassword, $storedPassword);
            } else {
                try {
                    $decrypted = Crypt::decryptString($storedPassword);
                } catch (\Exception $e) {
                    $decrypted = $storedPassword;
                }
                $isValid = hash_equals($decrypted, $inputPassword);
            }

            if (! $isValid) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid POS username or password.',
                ], 401);
            }

            return response()->json([
                'status'  => true,
                'message' => 'POS User login successful.',
                'user'    => [
                    'name'          => $posUser->name,
                    'company_id'    => $machine->company_id,
                    'serial_number' => $machine->serial_number,
                    'position'      => $posUser->position ?? null,
                    'type'          => 'POS User',
                ],
            ], 200);
        }

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        if (! $user->hasRole(['Company-admin', 'User'])) {
            return response()->json([
                'status'  => false,
                'message' => 'Only Company-admin can login.',
            ], 403);
        }

        if ($user->id != $machine->company_id) {
            return response()->json([
                'status'  => false,
                'message' => 'Serial number does not belong to your company.',
            ], 403);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid username or password.',
            ], 401);
        }

        if (! $license || now()->gt($license->license_validity)) {
            return response()->json([
                'status'  => false,
                'message' => 'Your license has expired. Please contact administrator.',
            ], 403);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Company Admin login successful.',
            'user'    => [
                'name'          => $user->name,
                'email'         => $user->email,
                'company_id'    => $machine->company_id,
                'serial_number' => $machine->serial_number,
                'position'      => 'Admin',
            ],
        ], 200);
    }

    public function masterlogin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username'      => 'required|string',
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
            ->where('name', $request->username)
            ->where('company_id', $machine->company_id)
            ->select('id', 'name', 'position', 'password', 'company_id')
            ->first();

        if ($posUser) {
            $vehicles = DB::table('vehicles')
                ->where('company_id', $machine->company_id)
                ->select('id', 'vehicle_type')
                ->get();

            return response()->json([
                'status'   => true,
                'message'  => 'POS User face login successful.',
                'user'     => [
                    'id'            => $posUser->id,
                    'name'          => $posUser->name,
                    'company_id'    => $machine->company_id,
                    'serial_number' => $machine->serial_number,
                    'position'      => $posUser->position ?? null,
                    'password'      => $posUser->password,
                    'type'          => 'POS User',
                ],
                'vehicles' => $vehicles, 
            ], 200);
        }

  
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        if (! $user->hasRole(['Company-admin', 'User'])) {
            return response()->json([
                'status'  => false,
                'message' => 'Only Company-admin or User can login.',
            ], 403);
        }

        if ($user->id != $machine->company_id) {
            return response()->json([
                'status'  => false,
                'message' => 'Serial number does not belong to your company.',
            ], 403);
        }

        $posUsers = DB::table('pos_users')
            ->where('company_id', $machine->company_id)
            ->select('id', 'name', 'position', 'password')
            ->get();


        $vehicles = DB::table('vehicles')
            ->where('company_id', $machine->company_id)
            ->select('id', 'vehicle_type')
            ->get();

        return response()->json([
            'status'    => true,
            'message'   => 'Admin face login successful.',
            'pos_users' => $posUsers,
            'vehicles'  => $vehicles,
        ], 200);
    }

}
