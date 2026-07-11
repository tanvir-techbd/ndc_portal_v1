<x-admin-layout :title="$notice->exists ? 'Edit Notice' : 'New Notice'">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>{{ $notice->exists ? 'Edit Notice' : 'Add / Edit Notice' }}</h3><div class="sub">Fill in the fields below and publish or save as draft</div></div>
    </div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ $notice->exists ? route('admin.notices.update', $notice) : route('admin.notices.store') }}">
        @csrf
        @if ($notice->exists) @method('PUT') @endif
        <div class="admin-form-grid">
          <div class="admin-form-group full">
            <label for="noticeTitle">Notice Title *</label>
            <input type="text" id="noticeTitle" name="title" value="{{ old('title', $notice->title) }}" required placeholder="e.g. Scheduled maintenance window: 15 July 2026"/>
            @error('title')<div class="admin-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="admin-form-group">
            <label for="noticeCategory">Category *</label>
            <select id="noticeCategory" name="category" required>
              @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ old('category', $notice->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group">
            <label for="noticeDate">Publish Date *</label>
            <input type="date" id="noticeDate" name="published_at" value="{{ old('published_at', $notice->published_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required/>
          </div>
          <div class="admin-form-group full">
            <label for="noticeBody">Notice Body *</label>
            <textarea id="noticeBody" name="body_html" required style="min-height:160px;">{{ old('body_html', $notice->body_html) }}</textarea>
            <div class="hint">Basic formatting tags (p, br, strong, em, ul/ol/li, a, h3/h4) are kept; everything else is stripped server-side.</div>
            @error('body_html')<div class="admin-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="admin-form-group">
            <label for="noticeVisibility">Visibility</label>
            <select id="noticeVisibility" name="visibility">
              <option value="public" {{ old('visibility', $notice->visibility ?? 'public') === 'public' ? 'selected' : '' }}>Public — visible on website</option>
              <option value="internal" {{ old('visibility', $notice->visibility) === 'internal' ? 'selected' : '' }}>Internal — admin only</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label for="noticeStatus">Status</label>
            <select id="noticeStatus" name="status">
              <option value="draft" {{ old('status', $notice->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="review" {{ old('status', $notice->status) === 'review' ? 'selected' : '' }}>In Review</option>
              <option value="published" {{ old('status', $notice->status) === 'published' ? 'selected' : '' }}>Published</option>
            </select>
          </div>
          <div class="admin-form-group full">
            <label for="attachment_media_id">Attachment</label>
            <select id="attachment_media_id" name="attachment_media_id">
              <option value="">No attachment</option>
              @foreach ($attachmentOptions as $asset)
                <option value="{{ $asset->id }}" {{ (string) old('attachment_media_id', $notice->attachment_media_id) === (string) $asset->id ? 'selected' : '' }}>{{ $asset->original_filename }}</option>
              @endforeach
            </select>
            <p class="hint">Upload a PDF/document from <a href="{{ route('admin.media.index') }}" target="_blank">Media Library</a> first (category: Notice Attachment), then select it here.</p>
          </div>
        </div>
        <div class="admin-panel-actions" style="padding-top:18px;">
          <a href="{{ route('admin.notices.index') }}" class="abtn">Cancel</a>
          <button type="submit" class="abtn abtn-primary">Save Notice</button>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>
