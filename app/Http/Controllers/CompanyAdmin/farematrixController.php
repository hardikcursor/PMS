<?php
namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Fare_metrix;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class farematrixController extends Controller
{
   public function index()
{
    $user = auth()->user();

    
    $faremetrix = Fare_metrix::with('vehicleCategory', 'company', 'slot')
        ->where('user_id', $user->id)
        ->get();

    $slots = Slot::all();

    return view('company-admin.fare-matrix.index', compact('faremetrix', 'slots'));
}


    public function vehicleadd()
    {
        $vehicles = Vehicle::all();
        return view('company-admin.fare-matrix.addvehicle', compact('vehicles'));
    }

    public function addvehicle(Request $request)
    {
        $request->validate([
            'vehicle_category' => 'required|string|max:255',
        ], [
            'vehicle_category.required' => 'Please enter a vehicle category.',
        ]);

        $vehicle = new Vehicle();

        $vehicle->vehicle_type = $request->vehicle_category;
        $vehicle->save();

        return redirect()->route('admin.faremetrix.vehicleadd')->with('success', 'Vehicle category added successfully.');
    }

    public function addslot()
    {
        $vehicleCategories = Vehicle::all();
        $slots             = Slot::all();
        $selectedCompany   = null;
        $companyCategories = User::role('company-admin')->get();

        return view('company-admin.fare-matrix.addslot', compact('vehicleCategories', 'companyCategories', 'slots', 'selectedCompany'));
    }
    public function updateRate(Request $request)
    {
        $data = $request->validate([
            'vehicle_id'      => 'required|integer',
            'rates'           => 'required|array',
            'rates.*.slot_id' => 'required|integer',
            'rates.*.rate'    => 'required|numeric|min:0',
        ]);

        foreach ($data['rates'] as $r) {
            Fare_Metrix::updateOrCreate(
                [
                    'vehicle_category_id' => $data['vehicle_id'],
                    'slot_id'             => $r['slot_id'],
                ],
                ['rate' => $r['rate']]
            );
        }

        return response()->json(['success' => true]);
    }

    public function deleteRate(Request $request)
    {
        $request->validate(['vehicle_id' => 'required|integer']);
        Fare_Metrix::where('vehicle_category_id', $request->vehicle_id)->delete();

        return response()->json(['success' => true]);
    }
}
