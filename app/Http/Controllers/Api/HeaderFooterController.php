<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;
use App\Models\Header;
use App\Models\User;

class HeaderFooterController extends Controller
{
     public function getHeaderFooter(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::where('name', $request->username)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        $headers = Header::where('user_id', $user->id)->first();
        $footer  = Footer::where('user_id', $user->id)->first();

        if (! $headers && ! $footer) {
            return response()->json([
                'status'  => false,
                'message' => 'No header or footer data found for this user',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => [
                'header' => $headers ? [
                    'header1' => $headers->header1,
                    'header2' => $headers->header2,
                    'header3' => $headers->header3,
                    'header4' => $headers->header4,
                ] : null,
                'footer' => $footer ? [
                    'footer1' => $footer->footer1,
                    'footer2' => $footer->footer2,
                    'footer3' => $footer->footer3,
                    'footer4' => $footer->footer4,
                ] : null,
            ],
        ]);
    }
}
