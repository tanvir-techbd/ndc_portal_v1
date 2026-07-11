<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private UserManagementService $users)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.users.index', [
            'users' => $this->users->list($request->only(['role', 'status', 'search'])),
        ]);
    }

    public function invite(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:content_editor,super_admin'],
        ]);

        $this->users->invite($request->user(), $data['name'], $data['email'], $data['role']);

        return back()->with('status', "Invite sent to {$data['email']}.");
    }

    public function suspend(Request $request, User $user): RedirectResponse
    {
        $this->users->suspend($request->user(), $user);

        return back()->with('status', 'User suspended.');
    }

    public function reactivate(Request $request, User $user): RedirectResponse
    {
        $this->users->reactivate($request->user(), $user);

        return back()->with('status', 'User reactivated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->users->delete($request->user(), $user);

        return back()->with('status', 'User deleted.');
    }
}
