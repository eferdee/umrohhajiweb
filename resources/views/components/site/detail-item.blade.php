@props(['label', 'span' => false])

<div class="{{ $span ? 'col-span-2 sm:col-span-3' : '' }}">
    <span class="detail-item-label">{{ $label }}</span>
    <span class="text-sm">{{ $slot }}</span>
</div>
