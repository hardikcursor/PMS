<?php
namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\PosMachine;
use App\Models\User;
use Illuminate\Http\Request;

class AddDeviceController extends Controller
{
    public function index()
    {
        $postmachine = PosMachine::with('company')->get();
        return view('company-admin.adddevices.index', compact('postmachine'));
    }

    public function create()
    {
        $company = User::role('company-admin')->get();
        return view('company-admin.adddevices.create', compact('company'));
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
        $posUser->status        = 1; // Default to active
        $posUser->save();

        return redirect()->route('admin.adddevices.index')->with('success', 'POS Device added successfully.');
    }

    public function changestatus(Request $request)
    {
        $machine = PosMachine::find($request->id);

        if ($machine) {
            $machine->status = $request->val;
            $machine->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }


        public function edit($id)
    {
        $postmachine = PosMachine::findOrFail($id);
        $company     = User::role('company-admin')->get();
        return view('company-admin.adddevices.edit', compact('postmachine', 'company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Cname'     => 'required|exists:users,id',
            'Srnumber'  => 'required|numeric',
            'AndroidId' => 'nullable|string|max:255',
        ]);

        // Find the existing POS user
        $posUser = PosMachine::findOrFail($id);
        $posUser->company_id    = $request->Cname;
        $posUser->serial_number = $request->Srnumber;
        $posUser->android_id    = $request->AndroidId;
        $posUser->save();

        return redirect()->route('admin.adddevices.index')->with('success', 'POS Device updated successfully.');
    }

    public function destroy($id)
    {
        $posUser = PosMachine::findOrFail($id);
        $posUser->delete();

        return redirect()->route('admin.adddevices.index')->with('success', 'POS Device deleted successfully.');
    }
}
