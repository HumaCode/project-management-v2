@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-center">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left"></i></a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $onEachSide = 1; // Number of pages to show on each side of the current page
                    
                    $showEllipsisStart = false;
                    $showEllipsisEnd = false;
                @endphp

                @for ($page = 1; $page <= $lastPage; $page++)
                    @php
                        $shouldShow = false;
                        if ($page === 1 || $page === $lastPage) {
                            $shouldShow = true;
                        } elseif (abs($page - $currentPage) <= $onEachSide) {
                            $shouldShow = true;
                        }
                    @endphp

                    @if ($shouldShow)
                        @if ($page == $currentPage)
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                        @endif
                    @else
                        @if ($page < $currentPage && !$showEllipsisStart)
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                            @php $showEllipsisStart = true; @endphp
                        @elseif ($page > $currentPage && !$showEllipsisEnd)
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                            @php $showEllipsisEnd = true; @endphp
                        @endif
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="bi bi-chevron-right"></i></a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
