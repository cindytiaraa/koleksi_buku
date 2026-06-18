<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function index()
    {
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        Log::info("OTP Verification Attempt - User: {$user->email}, Entered: {$request->otp}, DB OTP: {$user->otp}");

        if ($request->otp == $user->otp) {
            
            Log::info("OTP Verified Successfully for {$user->email}");

            $user->otp = null;
            $user->save();

            session(['otp_verified' => true]);

            // Redirect based on role (1=admin, 2=petugas, 3=user)
            if ($user->role === 1) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 2) {
                return redirect()->route('petugas.dashboard');
            }

            if ($user->role === 3) {
                return redirect()->route('user.dashboard');
            }

            if ($user->role === 4) {
                return redirect()->route('vendor.dashboard');
            }

            return redirect()->route('home');
        }

        Log::warning("OTP Verification Failed for {$user->email} - Entered: {$request->otp}, DB: {$user->otp}");
        return back()->with('error', 'OTP salah!');
    }
}