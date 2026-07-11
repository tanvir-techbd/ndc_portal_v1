@if ($paginator->hasPages())
  <div class="pagination">
    @if ($paginator->onFirstPage())
      <button class="pg-btn" disabled>&lsaquo; Prev</button>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="pg-btn">&lsaquo; Prev</a>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="pg-btn" style="cursor:default;">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="pg-btn active">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="pg-btn">Next &rsaquo;</a>
    @else
      <button class="pg-btn" disabled>Next &rsaquo;</button>
    @endif
  </div>
@endif
