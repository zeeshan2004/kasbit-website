@if($paginator->hasPages())
    <nav class="admin-pagination" aria-label="Pagination">
        <span class="admin-pagination-summary">
            Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </span>

        <div class="admin-pagination-links">
            @if($paginator->onFirstPage())
                <span class="is-disabled" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous page">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </a>
            @endif

            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if($page === $paginator->currentPage())
                    <span class="is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next page">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </a>
            @else
                <span class="is-disabled" aria-disabled="true">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
