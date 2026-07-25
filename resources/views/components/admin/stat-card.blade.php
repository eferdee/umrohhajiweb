@props(['label', 'value', 'icon' => null, 'accent' => 'var(--color-primary)'])

<div class="kpi-card p-5" style="--kpi-accent: {{ $accent }}">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs text-[var(--color-ink-soft)] font-medium truncate">{{ $label }}</p>
            <p class="font-display text-2xl text-[var(--color-ink)] mt-1.5 leading-none">{{ $value }}</p>
        </div>
        @if ($icon)
            <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: color-mix(in srgb, {{ $accent }} 12%, transparent); color: {{ $accent }};">
                {!! $icon !!}
            </span>
        @endif
    </div>
</div>
