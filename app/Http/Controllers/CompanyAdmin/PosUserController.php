<?php

namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use App\Models\PosUser;
use App\Models\User;
use Illuminate\Http\Request;

class PosUserController extends Controller
{
     public function index()
    {
        $posUsers = PosUser::with('company')->get();
        return view('company-admin.posuser.index', compact('posUsers'));
    }

      public function create()
    {
        $category = User::role('company-admin')->get();
        return view('company-admin.posuser.create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'  => 'required|exists:users,id',
            'user_rights' => 'required|string|max:255',
            'UserName'    => 'required|string|max:255',
            'login_id'    => 'required|string|max:255',
            'password'    => 'required|string|max:255',
        ]);

        try {
            $posuser              = new PosUser();
            $posuser->company_id  = $request->company_id;
            $posuser->user_rights = $request->user_rights;
            $posuser->UserName    = $request->UserName;
            $posuser->login_id    = $request->login_id;
            $posuser->password    = bcrypt($request->password);
            $posuser->save();

            return redirect()->route('admin.posuser.manageposuser')
                ->with('success', 'POS User added successfully.');

        } catch (\Illuminate\Database\QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) { // Duplicate entry MySQL error code
                return back()->with('error', '⚠️ This Login ID already exists. Please choose another one.')
                    ->withInput();
            }
            throw $ex;
        }
    }


    public function edit($id)
    {
        $posuser  = PosUser::findOrFail($id);
        $category = User::role('company-admin')->get();
        return view('company-admin.posuser.edit', compact('posuser', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id'  => 'required|exists:users,id',
            'user_rights' => 'required|string|max:255',
            'UserName'    => 'required|string|max:255',
            'login_id'    => 'required|string|max:255',
            'password'    => 'nullable|string|max:255',
        ]);

        try {
            $posuser = PosUser::findOrFail($id);
            $posuser->company_id  = $request->company_id;
            $posuser->user_rights = $request->user_rights;
            $posuser->UserName    = $request->UserName;
            $posuser->login_id    = $request->login_id;
            if ($request->filled('password')) {
                $posuser->password = bcrypt($request->password);
            }
            $posuser->save();

            return redirect()->route('admin.posuser.manageposuser')
                ->with('success', 'POS User updated successfully.');

        } catch (\Illuminate\Database\QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) { // Duplicate entry MySQL error code
                return back()->with('error', '⚠️ This Login ID already exists. Please choose another one.')
                    ->withInput();
            }
            throw $ex;
        }

        return back()->with('error', '⚠️ An error occurred while updating the POS User.')->withInput();


    }


    public function destroy($id)
    {
        $posuser = PosUser::findOrFail($id);
        $posuser->delete();

        return redirect()->route('admin.posuser.manageposuser')
            ->with('success', 'POS User deleted successfully.');
    }
}
