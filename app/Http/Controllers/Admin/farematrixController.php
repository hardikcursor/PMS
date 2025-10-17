<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fare_metrix;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class farematrixController extends Controller
{
    public function index(Request $request)
    {
        $selectedCompany = $request->get('user_id');

        $query = Fare_metrix::with('vehicleCategory', 'company', 'slot');

        if ($selectedCompany) {
            $query->where('user_id', $selectedCompany);
        }

        $faremetrix = $selectedCompany
            ? $query->get()
            : $query->paginate(10);

        $companyCategories = User::role('company-admin')->get();
        $slots             = Slot::all();

        return view('super-admin.fare-matrix.index', compact('faremetrix', 'companyCategories', 'slots', 'selectedCompany'));
    }

    public function vehicleadd()
    {
        $vehicles = Vehicle::all();
        return view('super-admin.fare-matrix.addvehicle', compact('vehicles'));
    }

    public function addvehicle(Request $request)
    {
        $request->validate([
            'vehicle_category' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Check if all characters are uppercase
                    if (! preg_match('/^[A-Z\s]+$/', $value)) {
                        $fail('The vehicle category must be in all capital letters.');
                    }
                },
            ],
        ], [
            'vehicle_category.required' => 'Please enter a vehicle category.',
        ]);

        $vehicle               = new Vehicle();
        $vehicle->vehicle_type = $request->vehicle_category;
        $vehicle->save();

        return redirect()
            ->route('superadmin.faremetrix.vehicleadd')
            ->with('success', 'Vehicle category added successfully.');
    }

    public function editvehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json($vehicle); // Return JSON for modal pre-fill
    }

    public function updatevehicle(Request $request, $id)
    {
        $request->validate([
            'vehicle_category' => 'required|string|max:255',
        ]);

        $vehicle               = Vehicle::findOrFail($id);
        $vehicle->vehicle_type = $request->vehicle_category;
        $vehicle->save();

        return redirect()->route('superadmin.faremetrix.vehicleadd')
            ->with('success', 'Vehicle category updated successfully.');
    }

    public function deletevehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('superadmin.faremetrix.vehicleadd')->with('success', 'Vehicle category deleted successfully.');
    }

    public function addslot()
    {
        $vehicleCategories = Vehicle::all();
        $slots             = Slot::all();
        $selectedCompany   = null;
        $companyCategories = User::role('company-admin')->get();

        return view('super-admin.fare-matrix.addslot', compact('vehicleCategories', 'companyCategories', 'slots', 'selectedCompany'));
    }

    public function ratecreate(Request $request)
    {
        $request->validate([
            'company_category'   => 'required|exists:users,id',
            'vehicle_category'   => 'required|array',
            'vehicle_category.*' => 'exists:vehicles,id',
            'rate'               => 'required|array',
        ]);

        $companyId = $request->company_category;

        foreach ($request->vehicle_category as $vehicleIndex => $vehicleCategoryId) {
            foreach ($request->rate as $slotId => $rates) {
                $rate = $rates[$vehicleIndex] ?? 0;

                // ✅ Duplicate check
                $exists = Fare_metrix::where('user_id', $companyId)
                    ->where('vehicle_category_id', $vehicleCategoryId)
                    ->where('slot_id', $slotId)
                    ->exists();

                if ($exists) {
                    return redirect()->route('superadmin.faremetrix.index')->back()->with('error', "Data already exists for this company, vehicle and slot.");
                }

                Fare_metrix::create([
                    'user_id'             => $companyId,
                    'vehicle_category_id' => $vehicleCategoryId,
                    'slot_id'             => $slotId,
                    'rate'                => $rate,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Fare metrix created successfully.');
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
