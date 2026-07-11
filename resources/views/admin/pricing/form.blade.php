<x-admin-layout :title="($tier->exists ? 'Edit' : 'Add') . ' Pricing Tier'">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div>
        <h3>{{ $tier->exists ? 'Edit' : 'Add' }} Pricing Tier</h3>
        <div class="sub">{{ $title }}</div>
      </div>
    </div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ $tier->exists ? route('admin.pricing.update', $tier->tier_key) : route('admin.pricing.store', $type) }}">
        @csrf
        @if ($tier->exists) @method('PUT') @endif
        <input type="hidden" name="type" value="{{ $type }}"/>
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="service_type">Service Type Key *</label>
            <input type="text" id="service_type" name="service_type" value="{{ old('service_type', $tier->service_type) }}" required pattern="^(cloud|rbs)_[a-z0-9_]+$"/>
            <p class="hint">Groups this tier under a pricing section. Must start with <code>cloud_</code> or <code>rbs_</code>, e.g. <code>cloud_ecs_general</code>.</p>
          </div>
          <div class="admin-form-group">
            <label for="name">Tier Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $tier->name) }}" required placeholder="e.g. General Purpose ECS — Small"/>
          </div>
          <div class="admin-form-group">
            <label for="price_value">Numeric Price (BDT)</label>
            <input type="number" id="price_value" name="price_value" step="0.01" min="0" value="{{ old('price_value', $tier->price_value) }}" placeholder="e.g. 3000"/>
            <p class="hint">Shown to customers as <strong>৳{{ number_format((float) old('price_value', $tier->price_value ?? 0), 2) }}</strong> — no need to type the ৳ sign or commas.</p>
          </div>
          <div class="admin-form-group">
            <label for="price_unit">Per</label>
            <select id="price_unit" name="price_unit">
              @foreach ($priceUnits as $unit)
                <option value="{{ $unit }}" {{ old('price_unit', $tier->price_unit ?? '/mo') === $unit ? 'selected' : '' }}>{{ $unit === '' ? '(none — flat price)' : trim($unit) }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group full">
            <label for="price_text">Custom Price Text</label>
            <input type="text" id="price_text" name="price_text" value="{{ old('price_text', $priceText) }}" maxlength="80" placeholder="e.g. Contact for Quote, or ৳2,000 – ৳5,000/hr (negotiable)"/>
            <p class="hint">Leave blank to auto-show the numeric price above. Required only if this tier has no fixed numeric price (e.g. a custom quote) — fill it in to override the auto-generated text for a compound price (e.g. a one-time fee plus a separate yearly renewal).</p>
          </div>
          <div class="admin-form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $tier->display_order ?? 0) }}"/>
            <p class="hint">Lower numbers appear first within the service type group.</p>
          </div>
          <div class="admin-form-group full">
            <label for="specs">Resource Details (one per line, <code>label: value</code>)</label>
            <textarea id="specs" name="specs" rows="6" placeholder="tier: Small&#10;resources: 2 vCPU, 4GB vMemory">{{ old('specs', $specsText) }}</textarea>
            <p class="hint">The <code>tier</code> line sets the small badge shown on the public pricing card (e.g. Basic / Standard / Premium / x.Small). Every other line shows as a resource-detail row on the card.</p>
          </div>
        </div>
        <div class="admin-panel-actions" style="padding-top:18px;">
          <a href="{{ route('admin.pricing.index', $type) }}" class="abtn">Cancel</a>
          <button type="submit" class="abtn abtn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>
