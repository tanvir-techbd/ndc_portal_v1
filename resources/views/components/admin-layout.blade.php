<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $title ?? 'Admin' }} – NDC Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;600;700&display=swap" rel="stylesheet"/>
  @vite(['resources/css/admin.css'])
</head>
<body class="admin-body">
<div class="admin-shell">
<aside class="admin-sidebar" id="adminSidebar">
  <div class="admin-sidebar-header">
    <div class="admin-sidebar-header-text">
      <strong>NDC Admin</strong>
      <span>Content Management</span>
    </div>
  </div>
  <nav class="admin-nav">
    <div class="admin-nav-group">
      <div class="admin-nav-label">Overview</div>
      <ul>
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><span class="nav-icon">&#9635;</span> Dashboard</a></li>
      </ul>
    </div>
    <div class="admin-nav-group">
      <div class="admin-nav-label">Content</div>
      <ul>
        <li class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.pages.index') ? route('admin.pages.index') : '#' }}"><span class="nav-icon">&#128196;</span> Site Pages</a></li>
        <li class="{{ request()->routeIs('admin.notices.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.notices.index') ? route('admin.notices.index') : '#' }}"><span class="nav-icon">&#128276;</span> News &amp; Notices</a></li>
        <li class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.services.index') ? route('admin.services.index') : '#' }}"><span class="nav-icon">&#9729;</span> Services Catalog</a></li>
        <li class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.team.index') ? route('admin.team.index') : '#' }}"><span class="nav-icon">&#128101;</span> Team Members</a></li>
        <li class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.media.index') ? route('admin.media.index') : '#' }}"><span class="nav-icon">&#128247;</span> Media Library</a></li>
        <li class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.messages.index') ? route('admin.messages.index') : '#' }}"><span class="nav-icon">&#9993;</span> Messages @php $newMsgCount = \App\Models\ContactInquiry::where('status', 'new')->count(); @endphp @if ($newMsgCount) <span class="nav-badge">{{ $newMsgCount }}</span> @endif</a></li>
      </ul>
    </div>
    <div class="admin-nav-group">
      <div class="admin-nav-label">Pricing Management</div>
      <ul>
        <li class="{{ request()->routeIs('admin.pricing.index') && (request()->route('type') ?? '') === 'cloud' ? 'active' : '' }}"><a href="{{ Route::has('admin.pricing.index') ? route('admin.pricing.index', 'cloud') : '#' }}"><span class="nav-icon">&#9729;</span> Cloud Based Pricing</a></li>
        <li class="{{ request()->routeIs('admin.pricing.index') && (request()->route('type') ?? '') === 'request' ? 'active' : '' }}"><a href="{{ Route::has('admin.pricing.index') ? route('admin.pricing.index', 'request') : '#' }}"><span class="nav-icon">&#128203;</span> Request Based Pricing</a></li>
      </ul>
    </div>
    <div class="admin-nav-group">
      <div class="admin-nav-label">Site Structure</div>
      <ul>
        <li class="{{ request()->routeIs('admin.pages.edit') && (request()->route('slug') ?? '') === 'home' ? 'active' : '' }}"><a href="{{ Route::has('admin.pages.edit') ? route('admin.pages.edit', 'home') : '#' }}"><span class="nav-icon">&#127968;</span> Homepage Builder</a></li>
      </ul>
    </div>
    <div class="admin-nav-group">
      <div class="admin-nav-label">Users</div>
      <ul>
        <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}"><span class="nav-icon">&#128100;</span> Admin Users</a></li>
      </ul>
    </div>
    <div class="admin-nav-group">
      <div class="admin-nav-label">System</div>
      <ul>
        <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><a href="{{ Route::has('admin.settings.edit') ? route('admin.settings.edit') : '#' }}"><span class="nav-icon">&#9881;</span> Site Settings</a></li>
        <li><a href="{{ url('/') }}" target="_blank"><span class="nav-icon">&#8617;</span> View Live Site</a></li>
        <li>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="admin-logout-link"><span class="nav-icon">&#9211;</span> Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
  <div class="admin-sidebar-footer">NDC Admin Panel v1.0<br>Bangladesh Computer Council</div>
</aside>
<div class="admin-main">
<div class="admin-topbar">
  <div class="admin-topbar-left">
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">&#9776;</button>
    <div>
      <div class="admin-page-title">{{ $title ?? 'Admin' }}</div>
      <div class="admin-breadcrumb"><a href="{{ route('admin.dashboard') }}" style="color:var(--gray-500);">Admin</a> / <span>{{ $title ?? 'Admin' }}</span></div>
    </div>
  </div>
  <div class="admin-topbar-right">
    <button class="admin-icon-btn" aria-label="Notifications">&#128276;<span class="dot"></span></button>
    <button class="admin-icon-btn" aria-label="Help">&#10067;</button>
    <div class="admin-user-chip" id="userChip">
      <div class="admin-user-avatar">{{ collect(explode(' ', auth()->user()->name ?? '?'))->map(fn($p) => substr($p, 0, 1))->take(2)->implode('') }}</div>
      <div class="admin-user-chip-text">
        <strong>{{ auth()->user()->name ?? 'Guest' }}</strong>
        <span>{{ auth()->user()?->role === 'super_admin' ? 'Super Admin' : 'Content Editor' }}</span>
      </div>
    </div>
  </div>
</div>
<div class="admin-content">
  @if (session('status'))
    <div class="admin-alert admin-alert-success">{{ session('status') }}</div>
  @endif

  {{ $slot }}

</div>
</div>
</div>
<script>
(function () {
  const toggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('adminSidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
  }
})();
</script>
</body>
</html>
