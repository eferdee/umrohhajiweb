@props(['variant' => 'angle', 'fill' => 'var(--color-paper)', 'flip' => false, 'class' => 'h-10 sm:h-14'])
{{-- Section divider geometris — varian selain wave, dipakai untuk memutus ritme antar section
     agar transisi terasa dirancang, bukan sekadar batas lurus. --}}
<div class="absolute inset-x-0 {{ $flip ? 'top-0 rotate-180' : 'bottom-0' }} pointer-events-none overflow-hidden leading-none">
    @if ($variant === 'angle')
        <svg class="relative block w-full {{ $class }}" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="{{ $fill }}" d="M0 80 L0 30 L720 62 L1440 8 L1440 80 Z"/>
        </svg>
    @elseif ($variant === 'notch')
        <svg class="relative block w-full {{ $class }}" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="{{ $fill }}" d="M0 80 L0 20 L640 20 L680 55 L760 55 L800 20 L1440 20 L1440 80 Z"/>
        </svg>
    @elseif ($variant === 'arch')
        {{-- Siluet kubah masjid berulang — identitas visual pengganti wave generik --}}
        <svg class="relative block w-full {{ $class }}" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true">
            <path fill="{{ $fill }}" d="M0 90 L0 46 C 24 46 24 20 48 20 C 72 20 72 46 96 46 C120 46 120 20 144 20 C168 20 168 46 192 46 C216 46 216 20 240 20 C264 20 264 46 288 46 L288 46 C312 46 312 14 336 14 C360 14 360 46 384 46 C408 46 408 20 432 20 C456 20 456 46 480 46 C504 46 504 20 528 20 C552 20 552 46 576 46 C600 46 600 20 624 20 C648 20 648 46 672 46 C696 46 696 12 720 12 C744 12 744 46 768 46 C792 46 792 20 816 20 C840 20 840 46 864 46 C888 46 888 20 912 20 C936 20 936 46 960 46 C984 46 984 14 1008 14 C1032 14 1032 46 1056 46 C1080 46 1080 20 1104 20 C1128 20 1128 46 1152 46 C1176 46 1176 20 1200 20 C1224 20 1224 46 1248 46 C1272 46 1272 46 1296 46 C1320 46 1320 20 1344 20 C1368 20 1368 46 1392 46 C1408 46 1424 46 1440 46 L1440 90 Z"/>
        </svg>
    @elseif ($variant === 'diagonal')
        <svg class="relative block w-full {{ $class }}" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="{{ $fill }}" d="M0 80 L0 55 L1440 5 L1440 80 Z"/>
        </svg>
    @else
        <svg class="relative block w-full {{ $class }}" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="{{ $fill }}" d="M0 70 Q 300 20 720 55 T 1440 40 V80 H0 Z"/>
        </svg>
    @endif
</div>
