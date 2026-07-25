@props(['eyebrow' => null, 'title', 'description' => null, 'crumbs' => []])

<section class="relative isolate overflow-hidden bg-[linear-gradient(160deg,var(--color-primary-dark)_0%,var(--color-primary)_55%,var(--color-primary-light)_120%)]">
    {{-- Lapisan latar dekoratif — identik dengan Landing Page --}}
    <div class="absolute inset-0 opacity-[0.07]" style="background-image:radial-gradient(circle at 20% 20%, white 1px, transparent 1px);background-size:26px 26px;"></div>
    <div class="absolute -right-24 -top-24 w-[24rem] h-[24rem] rounded-full bg-[var(--color-gold)]/20 blur-[100px]"></div>
    <div class="absolute -left-28 bottom-0 w-80 h-80 rounded-full bg-[var(--color-primary-light)]/30 blur-[100px]"></div>

    <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-10 sm:pt-14 pb-16 sm:pb-20 text-center" style="animation: fadeInUp .7s cubic-bezier(.22,.61,.36,1) both;">
        @if (count($crumbs))
            <nav class="flex items-center justify-center gap-1.5 text-xs text-white/55 mb-5" aria-label="Breadcrumb">
                @foreach ($crumbs as $label => $url)
                    @if (!$loop->first)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 shrink-0 text-white/30"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    @endif
                    @if ($url && !$loop->last)
                        <a href="{{ $url }}" class="hover:text-white transition-colors">{{ $label }}</a>
                    @else
                        <span class="text-white/80">{{ $label }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if ($eyebrow)
            <span class="relative inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs tracking-wide uppercase bg-white/10 text-[var(--color-gold-soft)] border border-white/15 backdrop-blur-sm overflow-hidden">
                <span class="absolute inset-0 rounded-full bg-[var(--color-gold)]/10 animate-[pulseGlow_2.6s_ease-in-out_infinite]"></span>
                <span class="relative">{{ $eyebrow }}</span>
            </span>
        @endif

        <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.85rem] leading-[1.15] tracking-tight text-white mt-5 text-balance">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="text-white/70 mt-4 max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
                {{ $description }}
            </p>
        @endif

        {{ $slot }}
    </div>

    {{-- Transisi organik & mulus ke section berikutnya — identik dengan Landing Page --}}
    <div class="absolute inset-x-0 bottom-0 pointer-events-none">
        <svg class="relative block w-full h-10 sm:h-14 lg:h-16" viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path fill="var(--color-primary-light)" opacity="0.22" d="M0 62 Q 240 12 480 56 T 960 50 T 1440 46 V120 H0 Z"/>
            <path fill="var(--color-paper)" d="M0 82 Q 300 32 720 72 T 1440 56 V120 H0 Z" style="filter: drop-shadow(0 -8px 16px rgba(15,23,42,0.14));"/>
        </svg>
    </div>
</section>
