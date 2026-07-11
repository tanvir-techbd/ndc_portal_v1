<x-public-layout :title="'Notices & Circulars – National Data Center'">

<div class="page-hero"><div class="wrap"><div class="page-hero-inner"><div class="page-hero-text"><div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>Notices &amp; Circulars</span></div><h2>Notices &amp; Circulars</h2><p>Official announcements, service updates, tender notices, policy circulars, and maintenance schedules from the National Data Center.</p></div></div></div></div>

<section class="section">
  <div class="wrap">
    <p class="eyebrow">Official Communications</p>
    <h2 class="section-title">Notices &amp; Circulars</h2>
    <div class="divider"></div>

    <div class="notice-stats">
      <div class="nstat"><div class="nstat-num">{{ $totalCount }}</div><div class="nstat-lbl">Total Notices</div></div>
      <div class="nstat"><div class="nstat-num">{{ \App\Models\Notice::public()->where('category', 'tender')->count() }}</div><div class="nstat-lbl">Tenders</div></div>
      <div class="nstat"><div class="nstat-num">{{ \App\Models\Notice::public()->where('category', 'maintenance')->count() }}</div><div class="nstat-lbl">Maintenance Notices</div></div>
      <div class="nstat"><div class="nstat-num">{{ \App\Models\Notice::public()->where('category', 'policy')->count() }}</div><div class="nstat-lbl">Policy Circulars</div></div>
    </div>

    <div class="notice-toolbar">
      <div class="filter-tabs">
        <a href="{{ route('notices') }}" class="ftab {{ ! $category ? 'active' : '' }}">All</a>
        @foreach ($categories as $cat)
          <a href="{{ route('notices', ['category' => $cat]) }}" class="ftab {{ $category === $cat ? 'active' : '' }}">{{ ucfirst($cat) }}</a>
        @endforeach
      </div>
    </div>

    <div class="notice-table-wrap">
    <table class="notice-table">
      <thead>
        <tr>
          <th style="width:120px;">Date</th>
          <th>Subject</th>
          <th style="width:120px;">Category</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($notices as $notice)
          <tr>
            <td><span class="notice-date-badge">{{ $notice->published_at->format('d M Y') }}</span></td>
            <td>
              <span class="notice-title-link">{{ $notice->title }}</span>
              @if ($notice->attachment && Route::has('notices.download'))
                <a href="{{ route('notices.download', $notice) }}" class="notice-download">&#128196; PDF</a>
              @endif
            </td>
            <td><span class="notice-cat cat-{{ $notice->category }}">{{ ucfirst($notice->category) }}</span></td>
          </tr>
        @empty
          <tr><td colspan="3" style="text-align:center;color:var(--gray-500);padding:24px;">No notices in this category yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    </div>

    <div style="margin-top:20px;">{{ $notices->links('vendor.pagination.ndc') }}</div>
  </div>
</section>

</x-public-layout>
