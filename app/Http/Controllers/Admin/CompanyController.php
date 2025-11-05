<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account_info;
use App\Models\Footer;
use App\Models\GST_INFO;
use App\Models\Header;
use App\Models\License;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{

    public function managecompany()
    {

        $expiredSubscriptions = Subscription::whereDate('duration', '<', now())->get();

        foreach ($expiredSubscriptions as $subscription) {
            $user = User::find($subscription->company_id);
            if ($user && $user->status == 1) {
                $user->status = 0;
                $user->save();
            }
        }

        $company      = User::with('license', 'subscriptionprice')->role(['Company-admin', 'User'])->get();
        $companyCount = $company->count();

        return view('super-admin.company.managecompany', compact('company', 'companyCount'));
    }

    public function create()
    {
        return view('super-admin.company.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'agency_name'      => 'required|string|max:255',
            'address'          => 'required|string|max:255',
            'phone_no'         => 'required|string|max:20',
            'phone'            => 'required|string|max:20',
            'fax'              => 'nullable|string|max:20',
            'email'            => 'required|email|unique:users,email',
            'username'         => 'required|alpha_dash|unique:users,username|max:50',
            'password'         => 'required|string|min:6',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'license_key'      => 'nullable|string|max:255',
            'license_validity' => 'required|date|after_or_equal:today',
            'android_version'  => 'nullable|string|max:50',
            'header1'          => 'nullable|string|max:24',
            'header2'          => 'nullable|string|max:24',
            'header3'          => 'nullable|string|max:24',
            'header4'          => 'nullable|string|max:24',
            'footer1'          => 'nullable|string|max:24',
            'footer2'          => 'nullable|string|max:24',
            'footer3'          => 'nullable|string|max:24',
            'footer4'          => 'nullable|string|max:24',
            'gst_no'           => 'nullable|string|max:50',
            'c_gst'            => 'nullable|numeric|min:0',
            's_gst'            => 'nullable|numeric|min:0',
            'cin_no'           => 'nullable|string|max:50',
            'pan_no'           => 'nullable|string|max:50',
            'role'             => 'required|string|max:50',
        ]);

        $user           = new User();
        $user->name     = $request->agency_name;
        $user->address  = $request->address;
        $user->phone_no = $request->phone_no;
        $user->phone    = $request->phone;
        $user->fax_no   = $request->fax;
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->position = "admin";
        $user->role     = $request->role;

        if ($request->hasFile('logo')) {
            $image    = $request->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('admin/uploads/company/'), $filename);
            $user->image = $filename;
        }

        $user->save();

        if ($user->role === 'admin') {
            $user->assignRole('Company-admin');
        } elseif ($user->role === 'user') {
            $user->assignRole('User');
        }

        // License
        $license                   = new License();
        $license->user_id          = $user->id;
        $license->license_key      = $request->license_key;
        $license->license_validity = $request->license_validity;
        $license->android_version  = $request->android_version;
        $license->save();

        // Header
        $header          = new Header();
        $header->user_id = $user->id;
        $header->header1 = $request->header1;
        $header->header2 = $request->header2;
        $header->header3 = $request->header3;
        $header->header4 = $request->header4;
        $header->save();

        // Footer
        $footer          = new Footer();
        $footer->user_id = $user->id;
        $footer->footer1 = $request->footer1;
        $footer->footer2 = $request->footer2;
        $footer->footer3 = $request->footer3;
        $footer->footer4 = $request->footer4;
        $footer->save();

        // GST
        $gstInfo             = new GST_INFO();
        $gstInfo->user_id    = $user->id;
        $gstInfo->gst_number = $request->gst_no;
        $gstInfo->c_gst      = $request->c_gst;
        $gstInfo->s_gst      = $request->s_gst;
        $gstInfo->save();

        // Account Info
        $accountInfo             = new Account_info();
        $accountInfo->user_id    = $user->id;
        $accountInfo->cin_number = $request->cin_no;
        $accountInfo->pan_number = $request->pan_no;
        $accountInfo->save();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Company created successfully');
    }

    public function edit($id)
    {

        $company = User::with(['license', 'header', 'footer', 'gstInfo', 'accountInfo'])->findOrFail($id);

        return view('super-admin.company.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'agency_name'      => 'required|string|max:255',
            'address'          => 'required|string|max:255',
            'phone_no'         => 'required|string|max:20',
            'fax'              => 'nullable|string|max:20',
            'email'            => 'required|email|unique:users,email,' . $id,
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'license_key'      => 'nullable|string|max:255',
            'license_validity' => 'nullable|date|after_or_equal:today',
            'android_version'  => 'nullable|string|max:50',

            'header1'          => 'nullable|string|max:15',
            'header2'          => 'nullable|string|max:15',
            'header3'          => 'nullable|string|max:15',
            'header4'          => 'nullable|string|max:15',
            'footer1'          => 'nullable|string|max:15',
            'footer2'          => 'nullable|string|max:15',
            'footer3'          => 'nullable|string|max:15',
            'footer4'          => 'nullable|string|max:15',

            'gst_no'           => 'nullable|string|max:50',
            'c_gst'            => 'nullable|numeric|min:0',
            's_gst'            => 'nullable|numeric|min:0',

            'cin_no'           => 'nullable|string|max:50',
            'pan_no'           => 'nullable|string|max:50',
        ]);

        // ✅ Find company
        $company = User::findOrFail($id);

        // ✅ Update company base info
        $company->update([
            'name'            => $request->agency_name,
            'address'         => $request->address,
            'phone_no'        => $request->phone_no,
            'fax'             => $request->fax,
            'email'           => $request->email,
            'android_version' => $request->android_version,
            'position'        => 'admin',
        ]);

        // ✅ Handle logo upload
        if ($request->hasFile('logo')) {
            $image    = $request->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('admin/uploads/company/'), $filename);
            $company->update(['image' => $filename]);
        }

        // ✅ Update or create related info safely
        $company->license()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'license_key'      => $request->license_key,
                'license_validity' => $request->license_validity,
            ]
        );

        $company->gstInfo()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'gst_number' => $request->gst_no,
                'c_gst'      => $request->c_gst ?? 0,
                's_gst'      => $request->s_gst ?? 0,
            ]
        );

        $company->accountInfo()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'cin_number' => $request->cin_no,
                'pan_number' => $request->pan_no,
            ]
        );

        $company->header()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'header1' => $request->header1,
                'header2' => $request->header2,
                'header3' => $request->header3,
                'header4' => $request->header4,
            ]
        );

        $company->footer()->updateOrCreate(
            ['user_id' => $company->id],
            [
                'footer1' => $request->footer1,
                'footer2' => $request->footer2,
                'footer3' => $request->footer3,
                'footer4' => $request->footer4,
            ]
        );

        return redirect()
            ->route('superadmin.company.manage')
            ->with('success', '✅ Company updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.company.manage')
            ->with('success', 'Company deleted successfully');
    }

    public function changestatus(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            $user->status = $request->val;
            $user->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function activate()
    {
        $active = User::with('subscriptionprice')->role(['Company-admin', 'User'])->where('status', 1)->get();
        return view('super-admin.company.active', compact('active'));
    }

    public function inactive()
    {
        $inactive = User::with('subscriptionprice')->role(['Company-admin', 'User'])->where('status', 0)->get();
        return view('super-admin.company.inactive', compact('inactive'));
    }
}
