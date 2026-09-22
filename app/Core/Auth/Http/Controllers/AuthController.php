<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('pages.auth.signin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $result = $this->authService->login(
                $request->only('email', 'password', 'remember'),
                $request->ip(),
                $request->userAgent()
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $result,
                ]);
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                throw $e;
            }

            return back()->withErrors($e->errors())->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('login');
    }

    public function logoutAll(Request $request)
    {
        $this->authService->logoutAllSessions($request->user());

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $this->authService->changePassword(
            $request->user(),
            $request->current_password,
            $request->password
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
        }

        return back()->with('success', 'Password changed successfully.');
    }

    public function enable2FA(Request $request)
    {
        $result = $this->authService->enableTwoFactor($request->user());

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function confirm2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $isValid = $this->authService->verifyTwoFactor($request->user(), $request->code);

        if ($isValid) {
            $request->user()->update(['two_factor_enabled' => true]);
            return response()->json(['success' => true, 'message' => '2FA enabled successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid code.'], 400);
    }

    public function disable2FA(Request $request)
    {
        $this->authService->disableTwoFactor($request->user());

        return response()->json(['success' => true, 'message' => '2FA disabled successfully.']);
    }

    public function sessions(Request $request)
    {
        $sessions = $request->user()->sessions()->latest()->get();

        if ($request->expectsJson()) {
            return response()->json(['data' => $sessions]);
        }

        return view('pages.auth.sessions', compact('sessions'));
    }

    public function revokeSession(Request $request, string $sessionId)
    {
        $request->user()->sessions()->where('id', $sessionId)->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Session revoked.');
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if ($request->expectsJson()) {
            return response()->json(['data' => $user]);
        }

        return view('pages.profile', compact('user'));
    }
}
