@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[var(--color-ink)] mb-1.5 tracking-wide']) }}>
    {{ $value ?? $slot }}
</label>
