@if ($paginator->hasPages())
    <div class="mt-6 flex justify-center">
        <div class="join items-center">
            {{-- Primera página --}}
            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->url(1) }}" class="join-item btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19L6 12l5-7M18 19l-5-7 5-7" />
                    </svg>
                </a>
            @endif

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="join-item btn btn-disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Página actual (activa pero sin acción) --}}
            <button class="join-item btn btn-active cursor-default pointer-events-none">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </button>

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="join-item btn btn-disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif

            {{-- Última página --}}
            @if ($paginator->currentPage() < $paginator->lastPage())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="join-item btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l5 7-5 7M6 5l5 7-5 7" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
@endif
