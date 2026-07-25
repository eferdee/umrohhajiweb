@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-5">
        <p class="text-xs text-[var(--color-ink-soft)] order-2 sm:order-1">
            Menampilkan <span class="font-medium text-[var(--color-ink)]">{{ $paginator->firstItem() }}</span>
            &ndash; <span class="font-medium text-[var(--color-ink)]">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium text-[var(--color-ink)]">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center gap-1.5 order-1 sm:order-2">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="action-icon-btn opacity-40 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="action-icon-btn" aria-label="Halaman sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-xs text-[var(--color-ink-soft)]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold bg-[var(--color-primary)] text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-medium text-[var(--color-ink-soft)] hover:bg-[var(--color-admin-surface-alt)] transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="action-icon-btn" aria-label="Halaman berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            @else
                <span class="action-icon-btn opacity-40 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </span>
            @endif
        </div>
    </div>
@endif
