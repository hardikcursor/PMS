<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosUser;
use App\Models\User;
use Illuminate\Http\Request;

class PosUserController extends Controller
{
    public function index()
    {
        $posUsers = PosUser::with('company')->get();
        return view('super-admin.posuser.index', compact('posUsers'));
    }

    public function create()
    {
        $category = User::role('company-admin')->get();
        return view('super-admin.posuser.create', compact('category'));
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

            return redirect()->route('superadmin.posuser.manageposuser')
                ->with('success', 'POS User added successfully.');

        } catch (\Illuminate\Database\QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) { // Duplicate entry MySQL error code
                return back()->with('error', '⚠️ This Login ID already exists. Please choose another one.')
                    ->withInput();
            }
            throw $ex;
        }
    }
}
