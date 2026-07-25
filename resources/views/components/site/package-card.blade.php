@props(['package', 'featured' => false])

@php
    $lowestPrice = $package->schedules_min_price ?? optional($package->schedules)->min('price');
    $scheduleCount = $package->schedules_count ?? optional($package->schedules)->count() ?? 0;
@endphp

<div class="reveal group relative rounded-[var(--radius-card)] border border-[var(--color-line)] overflow-hidden bg-[var(--color-surface)] h-full transition-all duration-300 ease-premium transform-gpu hover:-translate-y-2 hover:scale-[1.015] hover:shadow-2xl hover:shadow-[var(--color-primary)]/15 hover:border-[var(--color-primary)]/25 active:scale-[0.98] active:duration-150 {{ $featured ? 'lg:flex lg:items-stretch' : '' }}">
    <a href="{{ route('packages.show', $package) }}" class="absolute inset-0 z-10" aria-label="Lihat detail {{ $package->title }}"></a>

    <div class="{{ $featured ? 'h-52 lg:h-auto lg:w-[46%] lg:shrink-0' : 'h-44' }} bg-[var(--color-primary)] relative flex items-center justify-center overflow-hidden">
        @if ($package->thumbnail)
            <img src="{{ asset('storage/' . $package->thumbnail) }}" alt="{{ $package->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary-dark)]/70 via-[var(--color-primary-dark)]/10 to-transparent transition-opacity duration-300 group-hover:opacity-90"></div>
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-1/2 -top-1/2 h-[200%] w-1/3 rotate-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 -translate-x-[150%] group-hover:opacity-100 group-hover:translate-x-[280%] transition-all duration-700 ease-out"></div>
            </div>
        @else
            <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 30% 30%, white 1px, transparent 1px);background-size:20px 20px;"></div>
        @endif
        @if ($featured)
            <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 text-[11px] uppercase tracking-wide font-semibold px-3 py-1 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] shadow-sm">
                <x-site.emblem class="w-2.5 h-2.5" /> Unggulan
            </span>
        @elseif ($package->category)
            <span class="absolute top-4 left-4 text-[11px] uppercase tracking-wide font-medium px-3 py-1 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] shadow-sm">{{ $package->category->name }}</span>
        @endif
        <span class="absolute top-4 right-4 text-[11px] px-3 py-1 rounded-full bg-white/15 text-white backdrop-blur">{{ $package->duration }} Hari</span>
    </div>

    <div class="p-6 {{ $featured ? 'lg:flex-1 lg:flex lg:flex-col lg:justify-center lg:p-8' : '' }}">
        @if ($featured && $package->category)
            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">{{ $package->category->name }}</span>
        @endif
        <h3 class="font-display {{ $featured ? 'text-xl sm:text-2xl mt-1' : 'text-lg' }} transition-colors duration-300 group-hover:text-[var(--color-primary)] line-clamp-1">{{ $package->title }}</h3>
        <p class="text-sm text-[var(--color-ink-soft)] mt-2 leading-relaxed {{ $featured ? 'line-clamp-3' : 'line-clamp-2' }}">
            {{ Str::limit(strip_tags($package->description), $featured ? 170 : 110) }}
        </p>
        <div class="flex items-end justify-between mt-5 pt-5 border-t border-[var(--color-line)] group-hover:border-[var(--color-primary)]/20 transition-colors duration-300 gap-3">
            <div class="min-w-0">
                @if ($lowestPrice)
                    <span class="text-[10px] uppercase tracking-wide text-[var(--color-ink-soft)] block">Mulai dari</span>
                    <span class="font-display {{ $featured ? 'text-xl' : 'text-lg' }} text-[var(--color-primary)] block truncate">Rp {{ number_format($lowestPrice, 0, ',', '.') }}</span>
                @else
                    <span class="text-[10px] uppercase tracking-wide text-[var(--color-ink-soft)] block">Ketersediaan</span>
                    <span class="font-display text-[var(--color-primary)] block truncate">{{ $scheduleCount }} Jadwal Tersedia</span>
                @endif
            </div>
            <span class="pointer-events-none inline-flex items-center gap-1 text-sm font-medium text-[var(--color-primary)] shrink-0 group-hover:gap-2 transition-all duration-300 ease-premium">
                Lihat Detail
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-300 ease-premium group-hover:translate-x-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </span>
        </div>
    </div>
</div>
