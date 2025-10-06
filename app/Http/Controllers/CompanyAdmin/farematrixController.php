<?php

namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Fare_metrix;
use App\Models\Slot;
use App\Models\User;
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

        return view('company-admin.fare-matrix.index', compact('faremetrix', 'companyCategories', 'slots', 'selectedCompany'));
    }
}
