<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

      public function subscriptions(Request $request)
    {
       
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }
        $subscriptions = Subscription::where('company_id', $user->company_id ?? $user->id)
            ->get()
            ->map(function ($sub) {
                return [
                    'license_key' => $sub->name,
                    'price'       => $sub->price,
                    'created_at'  => $sub->created_at->format('Y-m-d'),
                    'Expiry Date'  => $sub->duration,
                ];
            });

        if ($subscriptions->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No subscriptions found for this user/company.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $subscriptions,
        ]);
    }

}
