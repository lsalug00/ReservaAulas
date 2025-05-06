@if ($paginator->hasPages())
    <div class="join mt-6 justify-center flex">
        {{-- Página anterior --}}
        @if ($paginator->onFirstPage())
            <span class="join-item btn btn-disabled">«</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn">«</a>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="join-item btn btn-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="join-item btn btn-active btn-primary">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="join-item btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Página siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn">»</a>
        @else
            <span class="join-item btn btn-disabled">»</span>
        @endif
    </div>
@endif
