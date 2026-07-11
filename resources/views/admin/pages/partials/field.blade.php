{{--
    Recursive field renderer for the structured page-content editor.
    Props: $name (bracket-notation input name, e.g. "blocks[timeline][0][year]"),
           $label, $value (the current value at this path, from content_blocks).

    Array values are auto-typed: a sequential array of scalars becomes a
    one-item-per-line textarea; a sequential array of arrays becomes a
    repeater of cards (existing rows + 2 blank spares to add new entries);
    an associative array recurses into labeled sub-fields. See
    PageController::reconstructValue() for the matching save-side logic —
    it must stay in sync with the type rules here.
--}}
@php
  $isList = is_array($value) && array_is_list($value);
  $isListOfAssoc = $isList && collect($value)->isNotEmpty() && collect($value)->every(fn ($v) => is_array($v));
  $isListOfScalars = $isList && ! $isListOfAssoc;
  $isAssoc = is_array($value) && ! $isList;
@endphp

@if ($isListOfAssoc)
  <div class="admin-form-group full">
    <label>{{ $label }}</label>
    @php
      $unionTemplate = [];
      foreach ($value as $row) { $unionTemplate += $row; }
      $blankRow = collect($unionTemplate)->map(fn ($v) => is_bool($v) ? false : (is_array($v) ? [] : ''))->all();
      $rows = array_merge($value, [$blankRow, $blankRow]);
    @endphp
    @foreach ($rows as $i => $row)
      <div class="pb-repeater-item">
        <div class="pb-repeater-item-label">{{ $label }} #{{ $i + 1 }}{{ $i >= count($value) ? ' (new — fill in to add)' : '' }}</div>
        @foreach ($row as $key => $val)
          @include('admin.pages.partials.field', ['name' => "{$name}[{$i}][{$key}]", 'label' => ucwords(str_replace('_', ' ', $key)), 'value' => $val])
        @endforeach
      </div>
    @endforeach
    <p class="hint">Leave a card completely blank to remove it when you save. Fill in a blank "(new)" card to add an entry.</p>
  </div>
@elseif ($isListOfScalars)
  <div class="admin-form-group full">
    <label>{{ $label }}</label>
    <textarea name="{{ $name }}" rows="{{ max(3, count($value)) }}">{{ implode("\n", $value) }}</textarea>
    <p class="hint">One item per line.</p>
  </div>
@elseif ($isAssoc)
  <div class="admin-form-group full">
    <label>{{ $label }}</label>
    <div class="pb-nested">
      @foreach ($value as $key => $val)
        @include('admin.pages.partials.field', ['name' => "{$name}[{$key}]", 'label' => ucwords(str_replace('_', ' ', $key)), 'value' => $val])
      @endforeach
    </div>
  </div>
@elseif (is_bool($value))
  <div class="admin-form-group">
    <label style="display:flex;align-items:center;gap:8px;">
      <input type="checkbox" name="{{ $name }}" value="1" style="width:auto;" {{ $value ? 'checked' : '' }}/>
      {{ $label }}
    </label>
  </div>
@else
  @php $isLong = is_string($value) && (strlen($value) > 90 || str_contains($value, "\n")); @endphp
  <div class="admin-form-group {{ $isLong ? 'full' : '' }}">
    <label>{{ $label }}</label>
    @if ($isLong)
      <textarea name="{{ $name }}" rows="4">{{ $value }}</textarea>
    @else
      <input type="text" name="{{ $name }}" value="{{ $value }}"/>
    @endif
  </div>
@endif
