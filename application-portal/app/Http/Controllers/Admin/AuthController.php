<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;
use App\Models\ActivityLog;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            ActivityLog::log('login', 'Administrator logged in');

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        ActivityLog::log('logout', 'Administrator logged out');

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'We have emailed your password reset link!')
            : back()->withInput($request->only('email'))->with('error', 'We could not find a user with that email address.');
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                ActivityLog::log('password_reset', 'Administrator reset their password', null, ['user_id' => $user->id]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', 'Password has been reset successfully!')
            : back()->withInput($request->only('email'))->with('error', 'This password reset token is invalid.');
    }
}