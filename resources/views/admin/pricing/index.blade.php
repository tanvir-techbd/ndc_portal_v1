<x-admin-layout :title="$title">
  @php
    $serviceTypeLabels = [
      'cloud_ecs_general' => 'General Purpose ECS',
      'cloud_ecs_memory' => 'Memory Intensive ECS',
      'cloud_ecs_accelerated' => 'Accelerated ECS (GPU)',
      'cloud_storage' => 'Elastic Block Storage (EBS) & Object Storage',
      'cloud_autoscaling' => 'Auto Scaling (AS)',
      'cloud_network' => 'Elastic IP & Load Balancer',
      'cloud_backup' => 'ECS Snapshot Backup Service',
      'cloud_container' => 'Container Service (eGovCloud Kubernetes)',
      'cloud_paas_app' => 'Hardened Application Platform',
      'cloud_paas_db' => 'Database Platform',
      'cloud_paas_messaging' => 'Messaging Platform',
      'cloud_paas_monitoring' => 'Monitoring Platform',
      'cloud_openshift' => 'OpenShift on BCC eGovCloud Platform',
      'cloud_dr' => 'Disaster Recovery Service (CDRS)',
      'rbs_vps' => 'VPS (Virtual Private Server)',
      'rbs_email' => 'Email Service',
      'rbs_database_managed' => 'Managed Database Service',
      'rbs_database_mysql' => 'MySQL Database Service',
      'rbs_private_access' => 'NDC Private Access Service',
      'rbs_public_ip' => 'Public IP Address',
      'rbs_block_storage' => 'Block Storage',
      'rbs_waf' => 'Web Application Firewall (WAF)',
      'rbs_backup' => 'Backup Service',
      'rbs_cloud_drive' => 'Cloud Drive Service',
      'rbs_colocation_rack_unit' => 'Rack Unit Allocation (Colocation)',
      'rbs_colocation_rack_space' => 'Rack Space Allocation (Colocation)',
      'rbs_physical_server' => 'Physical Server Service',
      'rbs_dns' => 'DNS Service',
      'rbs_consultation' => 'Design & Deployment Consultation',
    ];
  @endphp
  @foreach ($tiersByType as $serviceType => $tiers)
    <div class="admin-panel" style="margin-bottom:20px;">
      <div class="admin-panel-header">
        <div><h3>{{ $serviceTypeLabels[$serviceType] ?? str_replace('_', ' ', $serviceType) }}</h3><div class="sub">{{ $tiers->count() }} tiers &middot; {{ $serviceType }}</div></div>
        <div class="admin-panel-actions"><a href="{{ route('admin.pricing.create', ['type' => $type, 'service_type' => $serviceType]) }}" class="abtn abtn-primary abtn-sm">+ Add Tier</a></div>
      </div>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead><tr><th>Name</th><th>Resource Details</th><th>Price</th><th>Visible</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach ($tiers as $tier)
              <tr>
                <td>{{ $tier->name }}</td>
                <td>
                  @php $rowSpecs = collect($tier->specs ?? [])->except('tier'); @endphp
                  @if ($rowSpecs->isEmpty())
                    <span style="color:var(--gray-400);">&mdash;</span>
                  @else
                    <ul style="list-style:none;font-size:.76rem;color:var(--gray-500);line-height:1.6;">
                      @foreach ($rowSpecs as $label => $val)
                        <li><strong style="color:var(--gray-700);">{{ ucwords(str_replace('_', ' ', $label)) }}:</strong> {{ $val }}</li>
                      @endforeach
                    </ul>
                  @endif
                </td>
                <td>{{ $tier->price_display }}</td>
                <td><span class="status-pill {{ $tier->is_visible ? 'status-active' : 'status-inactive' }}">{{ $tier->is_visible ? 'Visible' : 'Hidden' }}</span></td>
                <td>
                  <div class="row-actions">
                    <a href="{{ route('admin.pricing.edit', [$type, $tier->tier_key]) }}" class="row-action-btn" title="Edit">&#9998;</a>
                    <form method="POST" action="{{ route('admin.pricing.toggle-visibility', $tier->tier_key) }}">
                      @csrf
                      <input type="hidden" name="type" value="{{ $type }}"/>
                      <button type="submit" class="row-action-btn" title="Toggle visibility">&#128065;</button>
                    </form>
                    @if (auth()->user()->isSuperAdmin())
                      <form method="POST" action="{{ route('admin.pricing.destroy', $tier->tier_key) }}" onsubmit="return confirm('Delete this pricing tier?');">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="type" value="{{ $type }}"/>
                        <button type="submit" class="row-action-btn danger" title="Delete">&#128465;</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endforeach
</x-admin-layout>
