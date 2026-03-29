<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Otp;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            if ($user && $user->is_admin) {
                SecurityEvent::record('admin_login', $request, userId: (int) $user->id, email: (string) $user->email);
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->route('home');
        }

        SecurityEvent::record('login_failed', $request, meta: [], userId: null, email: (string) $request->input('email'));

        return back()
            ->withErrors(['email' => app()->getLocale() === 'ar' ? 'بيانات الدخول غير صحيحة.' : 'Invalid credentials.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function createRegister()
    {
        return view('auth.register');
    }

    /**
     * Step 1: Validate registration data and send OTP.
     */
    public function storeRegister(RegisterRequest $request)
    {
        // Store registration data in session
        $request->session()->put('register_data', $request->validated());

        // Generate and send OTP
        $otp = Otp::generate($request->email, 'register');
        $this->sendOtpEmail($request->email, $otp->code, 'register');

        return redirect()->route('otp.verify', ['type' => 'register'])
            ->with('status', app()->getLocale() === 'ar'
                ? 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.'
                : 'Verification code sent to your email.');
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyOtp(Request $request)
    {
        $type = $request->query('type', 'register');

        if ($type === 'register' && ! $request->session()->has('register_data')) {
            return redirect()->route('register');
        }

        if ($type === 'reset_password' && ! $request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = $type === 'register'
            ? $request->session()->get('register_data.email')
            : $request->session()->get('reset_email');

        return view('auth.verify-otp', compact('type', 'email'));
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'type' => ['required', 'in:register,reset_password'],
        ]);

        $type = $request->type;

        $email = $type === 'register'
            ? $request->session()->get('register_data.email')
            : $request->session()->get('reset_email');

        if (! $email) {
            return redirect()->route($type === 'register' ? 'register' : 'password.request');
        }

        if (! Otp::verify($email, $request->code, $type)) {
            SecurityEvent::record('otp_verify_failed', $request, meta: ['type' => $type], userId: null, email: (string) $email);
            return back()->withErrors([
                'code' => app()->getLocale() === 'ar'
                    ? 'رمز التحقق غير صحيح أو منتهي الصلاحية.'
                    : 'Invalid or expired verification code.',
            ]);
        }

        if ($type === 'register') {
            return $this->completeRegistration($request);
        }

        // For password reset, mark as verified and redirect to reset form
        $request->session()->put('otp_verified', true);

        return redirect()->route('password.reset');
    }

    /**
     * Resend OTP code.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:register,reset_password'],
        ]);

        $type = $request->type;
        $email = $type === 'register'
            ? $request->session()->get('register_data.email')
            : $request->session()->get('reset_email');

        if (! $email) {
            return redirect()->route($type === 'register' ? 'register' : 'password.request');
        }

        $otp = Otp::generate($email, $type);
        $this->sendOtpEmail($email, $otp->code, $type);

        return back()->with('status', app()->getLocale() === 'ar'
            ? 'تم إعادة إرسال رمز التحقق.'
            : 'Verification code resent.');
    }

    /**
     * Complete registration after OTP verification.
     */
    private function completeRegistration(Request $request)
    {
        $data = $request->session()->pull('register_data');

        if (! $data) {
            return redirect()->route('register');
        }

        $user = User::create($data);
        Auth::login($user);

        return redirect()->route('home');
    }

    // ─── Forgot Password ─────────────────────────────────────────────

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP for password reset.
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => app()->getLocale() === 'ar'
                ? 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.'
                : 'No account found with this email address.',
        ]);

        $request->session()->put('reset_email', $request->email);

        $otp = Otp::generate($request->email, 'reset_password');
        $this->sendOtpEmail($request->email, $otp->code, 'reset_password');

        return redirect()->route('otp.verify', ['type' => 'reset_password'])
            ->with('status', app()->getLocale() === 'ar'
                ? 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.'
                : 'Verification code sent to your email.');
    }

    /**
     * Show reset password form (after OTP verified).
     */
    public function showResetPassword(Request $request)
    {
        if (! $request->session()->get('otp_verified') || ! $request->session()->get('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        if (! $request->session()->get('otp_verified') || ! $request->session()->get('reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->session()->pull('reset_email');
        $request->session()->forget('otp_verified');

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => app()->getLocale() === 'ar' ? 'حدث خطأ، حاول مرة أخرى.' : 'An error occurred. Please try again.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('status', app()->getLocale() === 'ar'
                ? 'تم تغيير كلمة المرور بنجاح.'
                : 'Password changed successfully.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * Send OTP code via email.
     */
    private function sendOtpEmail(string $email, string $code, string $type): void
    {
        $isAr = app()->getLocale() === 'ar';
        $mallName = $isAr ? config('mall.name.ar', 'المول') : config('mall.name.en', 'Mall');

        if ($type === 'register') {
            $subject = $isAr ? "رمز التحقق - {$mallName}" : "Verification Code - {$mallName}";
            $body = $isAr
                ? "رمز التحقق الخاص بك هو: {$code}\n\nهذا الرمز صالح لمدة 10 دقائق.\nلا تشارك هذا الرمز مع أي شخص."
                : "Your verification code is: {$code}\n\nThis code is valid for 10 minutes.\nDo not share this code with anyone.";
        } else {
            $subject = $isAr ? "استعادة كلمة المرور - {$mallName}" : "Password Reset - {$mallName}";
            $body = $isAr
                ? "رمز استعادة كلمة المرور الخاص بك هو: {$code}\n\nهذا الرمز صالح لمدة 10 دقائق.\nإذا لم تطلب استعادة كلمة المرور، تجاهل هذا البريد."
                : "Your password reset code is: {$code}\n\nThis code is valid for 10 minutes.\nIf you didn't request a password reset, ignore this email.";
        }

        try {
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
        } catch (\Exception $e) {
            // Log error silently - OTP is still stored in DB
            \Illuminate\Support\Facades\Log::error('OTP Email Error: ' . $e->getMessage());
        }
    }
}
