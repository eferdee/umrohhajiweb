@props(['name', 'checked' => false, 'label' => null, 'hint' => null])

{{--
    Tetap input checkbox HTML biasa (name/value 1, tidak terkirim saat unchecked) —
    100% kompatibel dengan $request->boolean('{{ $name }}') di controller, hanya tampilannya yang diubah.
--}}
<label class="flex items-start gap-3 cursor-pointer select-none">
    <span class="toggle-switch mt-0.5">
        <input type="checkbox" name="{{ $name }}" value="1" {{ $checked ? 'checked' : '' }} {{ $attributes }}>
        <span class="toggle-switch-track"></span>
        <span class="toggle-switch-thumb"></span>
    </span>
    @if ($label)
        <span>
            <span class="text-sm font-medium text-[var(--color-ink)]">{{ $label }}</span>
            @if ($hint)
                <span class="block text-xs text-[var(--color-ink-soft)] mt-0.5">{{ $hint }}</span>
            @endif
        </span>
    @endif
</label>
