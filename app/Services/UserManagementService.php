<?php

namespace App\Services;

use App\Mail\AdminInviteMail;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserManagementService
{
    public function __construct(private AuditService $audit)
    {
    }

    public function invite(User $invitedBy, string $name, string $email, string $role): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Str::random(40), // unusable placeholder until completeInvite() sets a real one
            'is_admin' => true,
            'role' => $role,
            'status' => 'pending',
            'invite_token' => Str::random(64),
            'invite_expires_at' => now()->addDays(7),
            'invited_by' => $invitedBy->id,
        ]);

        Mail::to($user->email)->send(new AdminInviteMail($user));

        $this->audit->record($invitedBy, 'user.invite', $user, ['role' => $role]);

        return $user;
    }

    public function suspend(User $actor, User $user): User
    {
        $user->forceFill(['status' => 'suspended'])->save();
        $this->audit->record($actor, 'user.suspend', $user);

        return $user;
    }

    public function reactivate(User $actor, User $user): User
    {
        $user->forceFill(['status' => 'active'])->save();
        $this->audit->record($actor, 'user.reactivate', $user);

        return $user;
    }

    public function delete(User $actor, User $user): void
    {
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->where('is_admin', true)->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'Cannot delete the last Super Admin account.',
            ]);
        }

        $this->audit->record($actor, 'user.delete', $user, ['email' => $user->email]);
        $user->delete();
    }

    /**
     * @param  array{role?: string, status?: string, search?: string}  $filters
     */
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return User::where('is_admin', true)
            ->when($filters['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate($perPage);
    }
}
