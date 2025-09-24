<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $companies = User::role('company-admin')->get();
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

        return redirect()->route('superadmin.subscription.manage')->with('success', 'Licence added successfully');
    }
}
