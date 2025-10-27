<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Models\Header;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    // public function getAddress(Request $request)
    // {
    //     // Validate input
    //     $request->validate([
    //         'lat' => 'required|numeric',
    //         'lng' => 'required|numeric',
    //     ]);

    //     $lat = $request->lat;
    //     $lng = $request->lng;

    //     $address = null;

    //     // 1️⃣ Try Google Maps API
    //     $googleKey = env('GOOGLE_MAPS_API_KEY');
    //     $googleUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$googleKey}&language=en&region=IN";

    //     $response = Http::get($googleUrl);

    //     if ($response->successful()) {
    //         $data = $response->json();

    //         if (! empty($data['results'][0]['formatted_address'])) {
    //             $address = $data['results'][0]['formatted_address'];
    //         }
    //     }

    //     // 2️⃣ Fallback to OpenStreetMap if Google fails
    //     if (! $address) {
    //         $osm = Http::get("https://nominatim.openstreetmap.org/reverse", [
    //             'lat'    => $lat,
    //             'lon'    => $lng,
    //             'format' => 'json',
    //         ])->json();

    //         $address = $osm['display_name'] ?? "Address not found";
    //     }

    //     // 3️⃣ Save to database
    //     $location = Location::create([
    //         'lat'     => $lat,
    //         'lng'     => $lng,
    //         'address' => $address,
    //     ]);

    //     return response()->json([
    //         'status'      => true,
    //         'address'     => $address,
    //         'location_id' => $location->id,
    //     ]);
    // }

 public function getAddress(Request $request)
{
    $request->validate([
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
    ]);

    $lat = $request->lat;
    $lng = $request->lng;
    $address = null;

   
    $googleKey = env('GOOGLE_MAPS_API_KEY');
    if ($googleKey) {
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$lat,$lng",
            'key' => $googleKey,
            'language' => 'en',
            'region' => 'IN'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['results'][0]['formatted_address'])) {
                $address = $data['results'][0]['formatted_address'];
            }
        }
    }

    
    if (!$address) {
        $osm = Http::get("https://nominatim.openstreetmap.org/reverse", [
            'lat' => $lat,
            'lon' => $lng,
            'format' => 'json'
        ])->json();

        $address = $osm['display_name'] ?? null;
    }

    
    if (!$address) {
        $address = "Lat: $lat, Lng: $lng";
    }

    
    $location = Location::updateOrCreate(
        ['lat' => $lat, 'lng' => $lng],
        ['address' => $address]
    );

    return response()->json([
        'status' => true,
        'address' => $address,
        'location_id' => $location->id
    ]);
}

}
