<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Admin authentication only — see LARAVEL-DYNAMIZATION-PLAN.md Part 6.
 * There is no public/portal auth flow; Jetstream's own Fortify-driven
 * /login and /register remain separate and untouched (see the plan's
 * "Jetstream (2026-07-11)" note on why both systems currently coexist).
 */
class AuthService
{
    public function login(string $email, string $password, bool $remember = false): User
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (! $user->is_admin) {
            throw ValidationException::withMessages([
                'email' => 'This account does not have admin panel access.',
            ]);
        }

        if ($user->status === 'pending') {
            throw ValidationException::withMessages([
                'email' => 'Your invite hasn\'t been completed yet. Check your email for the setup link.',
            ]);
        }

        if ($user->status === 'suspended') {
            throw ValidationException::withMessages([
                'email' => 'This account has been suspended.',
            ]);
        }

        Auth::login($user, $remember);
        request()->session()->regenerate();

        $user->forceFill(['last_login_at' => now()])->save();

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * The invited-user counterpart to register: sets the password on a
     * User row a Super Admin already created via UserManagementService::invite().
     */
    public function completeInvite(string $token, string $password): User
    {
        $user = User::where('invite_token', $token)
            ->where('status', 'pending')
            ->first();

        if (! $user || $user->invite_expires_at?->isPast()) {
            throw ValidationException::withMessages([
                'token' => 'This invite link is invalid or has expired.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($password),
            'status' => 'active',
            'invite_token' => null,
            'invite_expires_at' => null,
        ])->save();

        return $user;
    }
}
