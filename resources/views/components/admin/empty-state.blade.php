@props(['title' => 'Belum ada data', 'description' => null, 'icon' => null])

<div class="admin-empty">
    <div class="admin-empty-icon">
        {!! $icon ?? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>' !!}
    </div>
    <p class="text-sm font-semibold text-[var(--color-ink)]">{{ $title }}</p>
    @if ($description)
        <p class="text-xs text-[var(--color-ink-soft)] mt-1 max-w-xs">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-4">{{ $action }}</div>
    @endisset
</div>
