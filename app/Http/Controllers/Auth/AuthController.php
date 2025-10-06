<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function dologin(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ],[
            'email.required' => 'The email  is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password  is required.',
            'password.min' => 'The password must be at least 6 characters.',
        ]);
        $user = User::where('email', $request->email)->first();

        if ($user && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            if ($user->hasRole('Super-admin')) {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->hasRole('Company-admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login');
            }
        } else {
            return back()->with('error', 'Incorrect Email or password. Please try again.');
        }
        return back()->with('error', 'Invalid credentials');
    }

    
}
