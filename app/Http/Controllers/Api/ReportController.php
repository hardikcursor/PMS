<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\PosUser;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'bill_no'       => 'required|string|max:255',
    //         'vehicle_no'    => 'required|string|max:255',
    //         'vehicle_type'  => 'required|string|max:255',
    //         'duration_type' => 'required|string|max:255',
    //         'in_time'       => 'required|date_format:H:i',
    //         'out_time'      => 'required|date_format:H:i',
    //         'date'          => 'required|date',
    //         'amount'        => 'required|numeric',
    //         'serial_number' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'  => false,
    //             'error'   => $validator->errors(),
    //             'message' => 'Validation error',
    //         ], 422);
    //     }

    //     // Fetch POS User
    //     $posUser = PosUser::find($request->pos_user_id);
    //     if (! $posUser) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'POS User not found.',
    //         ], 404);
    //     }

    //     // Ensure POS User is operator
    //     if ($posUser->position !== 'operator') {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Only POS Users with position "operator" can push entries.',
    //         ], 403);
    //     }

    //     // Check the provided POS Machine is active
    //     $machine = PosMachine::where('serial_number', $request->serial_number)
    //         ->where('status', 1)
    //         ->first();

    //     if (! $machine) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Invalid or inactive POS machine.',
    //         ], 403);
    //     }

    //     // Ensure the machine belongs to the same company as the POS User
    //     if ($machine->company_id !== $posUser->company_id) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'This POS machine does not belong to the POS User company.',
    //         ], 403);
    //     }

    //     // Prepare entry data
    //     $data = $request->only([
    //         'bill_no', 'vehicle_no', 'vehicle_type', 'duration_type',
    //         'in_time', 'out_time', 'date', 'amount',
    //     ]);
    //     $data['pos_user_id']   = $posUser->id;
    //     $data['serial_number'] = $request->serial_number;
    //     $data['company_id']    = $posUser->company_id; // <-- add company_id

    //     // Create entry
    //     $entry = Report::create($data);

    //     // Return JSON
    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Report saved successfully',
    //         'data'    => [
    //             'id'            => $entry->id,
    //             'bill_no'       => $entry->bill_no,
    //             'vehicle_no'    => $entry->vehicle_no,
    //             'vehicle_type'  => $entry->vehicle_type,
    //             'duration_type' => $entry->duration_type,
    //             'in_time'       => $entry->in_time,
    //             'out_time'      => $entry->out_time,
    //             'date'          => $entry->date,
    //             'amount'        => $entry->amount,
    //             'serial_number' => $entry->serial_number,
    //             'company_id'    => $entry->company_id,
    //         ],
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_no'       => 'required|string|max:255',
            'vehicle_id'    => 'integer|exists:vehicles,id',
            'vehicle_no'    => 'required|string|max:255',
            'duration_type' => 'required|string|max:255',
            'in_time'       => 'required|date_format:H:i',
            'out_time'      => 'required|date_format:H:i',
            'date'          => 'required|date',
            'amount'        => 'required|numeric',
            'serial_number' => 'required|string',
            'name' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'error'   => $validator->errors(),
                'message' => 'Validation error',
            ], 422);
        }

        $machine = PosMachine::where('serial_number', $request->serial_number)
            ->where('status', 1)
            ->first();

        if (! $machine) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or inactive POS machine.',
            ], 403);
        }

        
        $posUser = PosUser::where('name', $request->name)->first();

        if (! $posUser) {
            return response()->json([
                'status' => false,
                'message' => 'POS user not found.',
            ], 404);
        }

     
        $data = $request->only([
            'bill_no',
            'vehicle_no',
            'vehicle_id',
            'duration_type',
            'in_time',
            'out_time',
            'date',
            'amount',
            'serial_number',
            'name',
        ]);

        $data['company_id']   = $machine->company_id ?? null;
        $data['pos_user_id']  = $posUser->id ?? null;

        $entry = Report::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Report saved successfully',
            'data'    => $entry,
        ], 201);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pos_user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'error'   => $validator->errors(),
                'message' => 'Validation error',
            ], 422);
        }

        $posUser = PosUser::find($request->pos_user_id);
        if (! $posUser) {
            return response()->json([
                'status'  => false,
                'message' => 'POS User not found.',
            ], 404);
        }

        $entries = Report::where('pos_user_id', $posUser->id)
            ->get()
            ->map(function ($entry) {
                return [
                    'id'            => $entry->id,
                    'bill_no'       => $entry->bill_no,
                    'vehicle_no'    => $entry->vehicle_no,
                    'vehicle_type'  => $entry->vehicle_type,
                    'duration_type' => $entry->duration_type,
                    'in_time'       => $entry->in_time,
                    'out_time'      => $entry->out_time,
                    'date'          => $entry->date,
                    'amount'        => $entry->amount,
                    'pos_user_id'   => $entry->pos_user_id,
                    'serial_number' => $entry->serial_number,
                    'created_at'    => $entry->created_at,
                    'updated_at'    => $entry->updated_at,
                ];
            });

        return response()->json([
            'status'  => true,
            'message' => 'Entries fetched successfully',
            'data'    => $entries,
        ], 200);
    }
}
