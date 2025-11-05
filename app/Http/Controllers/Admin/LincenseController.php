<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class LincenseController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('company', 'license')->get();

        return view('super-admin.addlincense.index', compact('subscriptions'));
    }

    public function create()
    {
        $companies = User::role(['company-admin', 'User'])->get();
        return view('super-admin.addlincense.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Company'          => 'required|exists:users,id',
            'SubscriptionName' => 'required|string|max:255',
            'Price'            => 'required|numeric|min:1',
            'license_validity' => 'required|date',
        ]);

        $subscription             = new Subscription();
        $subscription->company_id = $request->Company;
        $subscription->name       = $request->SubscriptionName;
        $subscription->price      = $request->Price;
        $subscription->duration   = $request->license_validity;
        $subscription->save();

        $user = User::find($request->Company);
        if ($user) {
            $user->status = 1;
            $user->save();
        }

      
        $license = \App\Models\License::where('user_id', $request->Company)->first();

        if ($license) {
            $license->license_validity = $request->license_validity;
            $license->save();
        }

        return redirect()->route('superadmin.subscription.manage')
            ->with('success', 'Licence added successfully & company enabled automatically');
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $companies    = User::role(['company-admin', 'User'])->get();
        return view('super-admin.addlincense.edit', compact('subscription', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Company'          => 'required|exists:users,id',
            'SubscriptionName' => 'required|string|max:255',
            'Price'            => 'required|numeric|min:1',
            'license_validity' => 'required|date',
        ]);

     
        $subscription             = Subscription::findOrFail($id);
        $subscription->company_id = $request->Company;
        $subscription->name       = $request->SubscriptionName;
        $subscription->price      = $request->Price;
        $subscription->duration   = $request->license_validity;
        $subscription->save();


        $license = License::where('user_id', $request->Company)->first();

        if ($license) {
            $license->license_validity = $request->license_validity;
            $license->save();
        }

        return redirect()
            ->route('superadmin.subscription.manage')
            ->with('success', 'Licence updated successfully & License table synced.');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('superadmin.subscription.manage')->with('success', 'Licence deleted successfully');
    }
}
