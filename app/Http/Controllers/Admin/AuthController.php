<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth)
    {
    }

    public function showLogin(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->canAccessAdminPanel()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->auth->login($credentials['email'], $credentials['password'], $request->boolean('remember'));

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->auth->logout();

        return redirect()->route('admin.login');
    }

    public function showAcceptInvite(string $token): View|RedirectResponse
    {
        $user = User::where('invite_token', $token)->where('status', 'pending')->first();

        if (! $user || $user->invite_expires_at?->isPast()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This invite link is invalid or has expired.',
            ]);
        }

        return view('admin.auth.accept-invite', ['token' => $token, 'user' => $user]);
    }

    public function acceptInvite(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->auth->completeInvite($data['token'], $data['password']);

        return redirect()->route('admin.login')->with('status', 'Password set — you can now log in.');
    }
}
