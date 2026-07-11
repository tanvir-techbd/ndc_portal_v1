<x-admin-layout :title="'Dashboard'">
  <div class="admin-stats-grid">
    <div class="admin-stat-card">
      <div class="admin-stat-card-top"><div class="admin-stat-icon blue">&#128196;</div></div>
      <div class="admin-stat-value">{{ $totalPages }}</div>
      <div class="admin-stat-label">Total Site Pages</div>
    </div>
    <div class="admin-stat-card">
      <div class="admin-stat-card-top">
        <div class="admin-stat-icon green">&#128276;</div>
        @if ($draftNotices > 0)
          <span class="admin-stat-trend down">{{ $draftNotices }} draft</span>
        @endif
      </div>
      <div class="admin-stat-value">{{ $publishedNotices }}</div>
      <div class="admin-stat-label">Notices Published</div>
    </div>
    <div class="admin-stat-card">
      <div class="admin-stat-card-top"><div class="admin-stat-icon gold">&#128100;</div></div>
      <div class="admin-stat-value">{{ auth()->user()->name }}</div>
      <div class="admin-stat-label">Logged in as ({{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Content Editor' }})</div>
    </div>
    <div class="admin-stat-card">
      <div class="admin-stat-card-top">
        <div class="admin-stat-icon red">&#128100;</div>
        @if ($suspendedAdminUsers > 0)
          <span class="admin-stat-trend down">{{ $suspendedAdminUsers }} suspended</span>
        @endif
      </div>
      <div class="admin-stat-value">{{ $adminUsersCount }}</div>
      <div class="admin-stat-label">Admin Users</div>
    </div>
  </div>

  <div class="admin-panel" style="margin-top:22px;">
    <div class="admin-panel-header">
      <div><h3>Recent Activity</h3><div class="sub">Latest admin actions across the panel</div></div>
    </div>
    <div class="admin-panel-body" style="padding:0 22px;">
      <ul class="activity-feed">
        @forelse ($recentActivity as $entry)
          <li class="activity-item">
            <div class="activity-avatar" style="background:var(--green-pale);color:var(--green-dark);">&#9679;</div>
            <div>
              <div class="activity-text"><strong>{{ $entry->user?->name ?? 'System' }}</strong> — {{ str_replace('_', ' ', str_replace('.', ' ', $entry->action)) }}</div>
              <div class="activity-time">{{ $entry->created_at->diffForHumans() }}</div>
            </div>
          </li>
        @empty
          <li class="activity-item"><div class="activity-text" style="color:var(--gray-500);padding:16px 0;">No activity recorded yet.</div></li>
        @endforelse
      </ul>
    </div>
  </div>
</x-admin-layout>
