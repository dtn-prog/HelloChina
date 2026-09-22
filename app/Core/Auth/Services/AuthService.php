<?php

namespace App\Core\Auth\Services;

use App\Models\User;
use App\Core\Auth\Models\LoginHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            $this->logLoginAttempt(null, $credentials['email'], $ipAddress, $userAgent, 'failed', 'Invalid credentials');
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        if ($user->status !== 'active') {
            $this->logLoginAttempt($user, $user->email, $ipAddress, $userAgent, 'failed', 'Account ' . $user->status);
            throw ValidationException::withMessages([
                'email' => ['Your account has been ' . $user->status . '.'],
            ]);
        }

        if ($this->hasExcessiveFailedAttempts($user)) {
            $this->logLoginAttempt($user, $user->email, $ipAddress, $userAgent, 'failed', 'Too many attempts');
            throw ValidationException::withMessages([
                'email' => ['Too many failed login attempts. Please try again later.'],
            ]);
        }

        Auth::login($user, $credentials['remember'] ?? false);

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);

        $this->logLoginAttempt($user, $user->email, $ipAddress, $userAgent, 'success');

        return [
            'user' => $user,
            'token' => $user->createToken('admin-token')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        Auth::logout();
    }

    public function logoutAllSessions(User $user): void
    {
        $user->tokens()->delete();
        Auth::logout();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update(['password' => $newPassword]);
    }

    public function resetPassword(User $user, string $password): void
    {
        $user->update(['password' => $password]);
        $user->tokens()->delete();
    }

    public function verifyTwoFactor(User $user, string $code): bool
    {
        if (!$user->two_factor_enabled) {
            return true;
        }

        $google2fa = app('pragmarx.google2fa');
        return $google2fa->verifyKey($user->two_factor_secret, $code);
    }

    public function enableTwoFactor(User $user): array
    {
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();

        $user->update(['two_factor_secret' => $secret]);

        return [
            'secret' => $secret,
            'qr_code' => $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            ),
        ];
    }

    public function disableTwoFactor(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);
    }

    protected function logLoginAttempt(?User $user, string $email, ?string $ipAddress, ?string $userAgent, string $status, ?string $reason = null): void
    {
        LoginHistory::create([
            'user_id' => $user?->id,
            'email' => $email,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'device' => $this->parseDevice($userAgent),
            'status' => $status,
            'failure_reason' => $reason,
        ]);
    }

    protected function hasExcessiveFailedAttempts(User $user, int $maxAttempts = 5, int $minutes = 15): bool
    {
        return LoginHistory::where('user_id', $user->id)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count() >= $maxAttempts;
    }

    protected function parseDevice(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        } elseif (str_contains($userAgent, 'Mac')) {
            return 'macOS';
        } elseif (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            return 'Android';
        } elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }

        return 'Unknown';
    }
}
