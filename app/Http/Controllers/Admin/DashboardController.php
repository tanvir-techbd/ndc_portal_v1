<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Page;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AuditService $audit): View
    {
        return view('admin.dashboard', [
            'user' => auth()->user(),
            'totalPages' => Page::count(),
            'publishedNotices' => Notice::where('status', 'published')->count(),
            'draftNotices' => Notice::where('status', '!=', 'published')->count(),
            'adminUsersCount' => User::where('is_admin', true)->count(),
            'suspendedAdminUsers' => User::where('is_admin', true)->where('status', 'suspended')->count(),
            'recentActivity' => $audit->recent(8),
        ]);
    }
}
