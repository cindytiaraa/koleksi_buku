<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post login redirect path.
     */
    

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // Get authenticated user
        /** @var User $user */
        $user = $this->guard()->user();

        // Generate and send OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        Log::info("Before save - User ID: {$user->id}, OTP: {$otp}");
        
        try {
            $user->otp = $otp;
            $saved = $user->save();
            
            Log::info("After save - Saved: " . ($saved ? 'true' : 'false'));
        } catch (\Exception $e) {
            Log::error("Error saving OTP: " . $e->getMessage());
            Log::error($e->getTraceAsString());
        }

        try {
            Mail::raw("Kode OTP anda adalah: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Login');
            });
            Log::info("OTP {$otp} sent to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP to {$user->email}: " . $e->getMessage());
        }

        return $this->authenticated($request, $user)
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * After password authentication, redirect to OTP form
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('otp.form');
    }

    /**
     * Redirect berdasarkan role setelah OTP verifikasi
     * (dipanggil dari OtpController setelah OTP valid)
     */
    public static function redirectByRole($user): string
    {
        return match((int) $user->role) {
            1 => route('admin.dashboard'),
            2 => route('petugas.dashboard'),
            3 => route('user.dashboard'),
            4 => route('vendor.dashboard'),
            default => '/login', // fallback jika role tidak dikenali
        };
    }
}