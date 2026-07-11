<x-admin-layout :title="$member->exists ? 'Edit Team Member' : 'Add Team Member'">
  <div class="admin-panel">
    <div class="admin-panel-header"><div><h3>{{ $member->exists ? 'Edit' : 'Add' }} Team Member</h3></div></div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}">
        @csrf
        @if ($member->exists) @method('PUT') @endif
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" required/>
          </div>
          <div class="admin-form-group">
            <label for="designation">Designation *</label>
            <input type="text" id="designation" name="designation" value="{{ old('designation', $member->designation) }}" required/>
          </div>
          <div class="admin-form-group">
            <label for="group">Group *</label>
            <select id="group" name="group" required>
              <option value="leadership" {{ old('group', $member->group ?? 'leadership') === 'leadership' ? 'selected' : '' }}>Leadership &amp; Management</option>
              <option value="technical_staff" {{ old('group', $member->group) === 'technical_staff' ? 'selected' : '' }}>Technical Staff</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $member->display_order ?? 0) }}"/>
          </div>
          <div class="admin-form-group full">
            <label for="photo_media_id">Photo</label>
            <select id="photo_media_id" name="photo_media_id">
              <option value="">No photo</option>
              @foreach ($photoOptions as $asset)
                <option value="{{ $asset->id }}" {{ (string) old('photo_media_id', $member->photo_media_id) === (string) $asset->id ? 'selected' : '' }}>{{ $asset->original_filename }}</option>
              @endforeach
            </select>
            <p class="hint">Upload a photo from <a href="{{ route('admin.media.index') }}" target="_blank">Media Library</a> first (category: Team Photo), then select it here.</p>
          </div>
        </div>
        <div class="admin-panel-actions" style="padding-top:18px;">
          <a href="{{ route('admin.team.index') }}" class="abtn">Cancel</a>
          <button type="submit" class="abtn abtn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>
