<x-admin-layout :title="($service->exists ? 'Edit' : 'Add') . ' Service'">
  <div class="admin-panel">
    <div class="admin-panel-header"><div><h3>{{ $service->exists ? 'Edit' : 'Add' }} Service</h3></div></div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($service->exists) @method('PUT') @endif
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="kind">Type *</label>
            <select id="kind" name="kind" required>
              <option value="group" {{ old('kind', $service->kind) === 'group' ? 'selected' : '' }}>Group Summary (homepage + section header)</option>
              <option value="detail" {{ old('kind', $service->kind ?? 'detail') === 'detail' ? 'selected' : '' }}>Catalog Card (detail under a group)</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label for="group_slug">Parent Group</label>
            <select id="group_slug" name="group_slug">
              <option value="">— none (only used by Catalog Cards) —</option>
              @foreach ($groupOptions as $slug => $name)
                <option value="{{ $slug }}" {{ old('group_slug', $service->group_slug) === $slug ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
            <p class="hint">Only applies when Type is "Catalog Card" — picks which section on the Services page this card appears under.</p>
          </div>
          <div class="admin-form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required/>
          </div>
          <div class="admin-form-group">
            <label for="tag">Tag / Subtitle</label>
            <input type="text" id="tag" name="tag" value="{{ old('tag', $service->tag) }}" placeholder="e.g. Infrastructure as a Service"/>
          </div>
          <div class="admin-form-group">
            <label for="icon">Icon</label>
            <select id="icon" name="icon">
              <option value="">Default</option>
              @foreach (\App\Http\Controllers\Admin\ServiceController::ICONS as $iconKey)
                <option value="{{ $iconKey }}" {{ old('icon', $service->icon) === $iconKey ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $iconKey)) }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $service->display_order ?? 0) }}"/>
          </div>
          <div class="admin-form-group full">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
          </div>
          <div class="admin-form-group full">
            <label for="tiers">Tier Pills (one per line)</label>
            <textarea id="tiers" name="tiers" rows="4">{{ old('tiers', $tiersText) }}</textarea>
            <p class="hint">End a line with <code>*</code> to highlight it in gold (e.g. <code>Complimentary Add-on*</code>).</p>
          </div>
          <div class="admin-form-group full">
            <label for="features">Feature List (one per line)</label>
            <textarea id="features" name="features" rows="5">{{ old('features', $featuresText) }}</textarea>
          </div>
          <div class="admin-form-group">
            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="is_featured" value="1" style="width:auto;" {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}/> Featured on homepage grid</label>
            <p class="hint">Only meaningful for a Group Summary.</p>
          </div>
          <div class="admin-form-group">
            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="is_visible" value="1" style="width:auto;" {{ old('is_visible', $service->exists ? $service->is_visible : true) ? 'checked' : '' }}/> Visible on the public site</label>
          </div>
        </div>
        <div class="admin-panel-actions" style="padding-top:18px;">
          <a href="{{ route('admin.services.index') }}" class="abtn">Cancel</a>
          <button type="submit" class="abtn abtn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>
