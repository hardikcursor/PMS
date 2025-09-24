<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\User;
use Illuminate\Http\Request;

class AddDeviceController extends Controller
{

    public function index()
    {
        $postmachine = PosMachine::with('company')->get();
        return view('super-admin.adddevices.index', compact('postmachine'));
    }
    public function create()
    {
        $company = User::role('company-admin')->get();
        return view('super-admin.adddevices.create', compact('company'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Cname'     => 'required|exists:users,id',
            'Srnumber'  => 'required|numeric',
            'AndroidId' => 'nullable|string|max:255',
        ]);

        // Create the new POS user
        $posUser                = new PosMachine();
        $posUser->company_id    = $request->Cname;
        $posUser->serial_number = $request->Srnumber;
        $posUser->android_id    = $request->AndroidId;
        $posUser->save();

        return redirect()->route('superadmin.posusers.create')->with('success', 'POS Device added successfully.');
    }
}
