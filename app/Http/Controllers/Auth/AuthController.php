<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    // public function dologin(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6',
    //     ],[
    //         'email.required' => 'The email  is required.',
    //         'email.email' => 'Please enter a valid email address.',
    //         'password.required' => 'The password  is required.',
    //         'password.min' => 'The password must be at least 6 characters.',
    //     ]);
    //     $user = User::where('email', $request->email)->first();

    //     if ($user && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

    //         if ($user->hasRole('Super-admin')) {
    //             return redirect()->route('superadmin.dashboard');
    //         } elseif ($user->hasRole('Company-admin')) {
    //             return redirect()->route('admin.dashboard');
    //         } else {
    //             Auth::logout();
    //             return redirect()->route('login');
    //         }
    //     } else {
    //         return back()->with('error', 'Incorrect Email or password. Please try again.');
    //     }
    //     return back()->with('error', 'Invalid credentials');
    // }

    // public function dologin(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ], [
    //         'username.required' => 'The username is required.',
    //         'password.required' => 'The password is required.',
    //         'password.min'      => 'The password must be at least 6 characters.',
    //     ]);

    //     $user = User::where('username', $request->username)->first();

    //     if (! $user) {
    //         return back()
    //             ->with('error', 'Username not found. Please check and try again.');
    //     }

    //     if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
    //         $user = Auth::user();

    //         if ($user->hasAnyRole(['Company-admin', 'User'])) {
    //             if ($user->status == 1) {
    //                 if ($user->hasRole('Company-admin')) {
    //                     return redirect()->route('admin.dashboard');
    //                 } elseif ($user->hasRole('User')) {
    //                     return redirect()->route('user.dashboard');
    //                 }
    //             } else {
    //                 Auth::logout();
    //                 return back()
    //                     ->with('error', 'Your account is inactive. Please contact the administrator.')
    //                     ->withInput();
    //             }
    //         } elseif ($user->hasRole('Super-admin')) {
    //             return redirect()->route('superadmin.dashboard');
    //         } else {
    //             Auth::logout();
    //             return back()
    //                 ->with('error', 'You are not authorized to log in.')
    //                 ->withInput();
    //         }
    //     }
    //     return back()
    //         ->with('error', 'Incorrect password. Please try again.')
    //         ->withInput();
    // }

    public function dologin(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'The username is required.',
            'password.required' => 'The password is required.',
            'password.min'      => 'The password must be at least 6 characters.',
        ]);

        
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->with('error', 'Username not found. Please check and try again.');
        }

      
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            $license = $user->license;

            if ($license && Carbon::parse($license->license_validity)->isPast()) {
               
                $user->update(['status' => 0]);
                Auth::logout();

                return back()
                    ->with('error', 'Your license has expired. Please contact the administrator.')
                    ->withInput();
            }

            
            if ($user->hasAnyRole(['Company-admin', 'User'])) {
                if ($user->status == 1) {
                    if ($user->hasRole('Company-admin')) {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->hasRole('User')) {
                        return redirect()->route('user.dashboard');
                    }
                } else {
                    Auth::logout();
                    return back()
                        ->with('error', 'Your account is inactive. Please contact the administrator.')
                        ->withInput();
                }
            } elseif ($user->hasRole('Super-admin')) {
                return redirect()->route('superadmin.dashboard');
            } else {
                Auth::logout();
                return back()
                    ->with('error', 'You are not authorized to log in.')
                    ->withInput();
            }
        }

        // Step 6: Invalid password
        return back()
            ->with('error', 'Incorrect password. Please try again.')
            ->withInput();
    }

}
