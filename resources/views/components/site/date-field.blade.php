@props([
    'label',
    'model',                 // ekspresi Alpine, mis. "p.birth_date"
    'name',                  // ekspresi Alpine untuk atribut name, mis. "`pilgrims[${index}][birth_date]`"
    'required' => false,
    'minYear' => 1900,
    'maxYear' => null,
    'minDate' => null,       // format Y-m-d
    'maxDate' => null,       // format Y-m-d
    'defaultYear' => null,
    'placeholder' => 'Pilih tanggal',
    'errorText' => null,     // ekspresi Alpine boolean/string untuk pesan error real-time
])

<div class="relative"
    x-data="sitePicker(() => {{ $model }}, (v) => { {{ $model }} = v }, {
        minYear: {{ (int) $minYear }},
        maxYear: {{ $maxYear !== null ? (int) $maxYear : 'null' }},
        minDate: {{ $minDate ? "'" . $minDate . "'" : 'null' }},
        maxDate: {{ $maxDate ? "'" . $maxDate . "'" : 'null' }},
        defaultYear: {{ $defaultYear !== null ? (int) $defaultYear : 'null' }},
    })"
    @keydown.escape.window="close()" @click.outside="close()">

    <label class="site-field-label">{{ $label }}@if($required)<span class="text-[var(--color-danger)]"> *</span>@endif</label>

    <input type="hidden" :name="{{ $name }}" :value="{{ $model }}">

    <button type="button" @click="toggle()" class="site-dp-trigger" :class="{ 'is-open': open @if($errorText), 'is-invalid': {{ $errorText }} @endif }">
        <span :class="displayValue ? '' : 'text-[var(--color-ink-soft)]'" x-text="displayValue || '{{ $placeholder }}'"></span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-ink-soft)] shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
        </svg>
    </button>

    @if($errorText)
        <p class="site-field-error" x-show="{{ $errorText }}" x-cloak x-text="{{ $errorText }}"></p>
    @endif

    <div x-show="open" x-cloak x-transition.origin.top.duration.150ms x-ref="panel" class="site-dp-panel">
        <div class="flex items-center gap-1.5 mb-3">
            <button type="button" @click="prevMonth()" aria-label="Bulan sebelumnya" class="site-dp-nav">&lsaquo;</button>
            <select x-model.number="viewMonth" class="site-dp-select flex-1" aria-label="Pilih bulan">
                <template x-for="(m, i) in monthNames" :key="i">
                    <option :value="i" x-text="m"></option>
                </template>
            </select>
            <select x-model.number="viewYear" class="site-dp-select w-[5.5rem]" aria-label="Pilih tahun">
                <template x-for="y in years" :key="y">
                    <option :value="y" x-text="y"></option>
                </template>
            </select>
            <button type="button" @click="nextMonth()" aria-label="Bulan berikutnya" class="site-dp-nav">&rsaquo;</button>
        </div>

        <div class="grid grid-cols-7 gap-1 text-center text-[0.65rem] font-medium text-[var(--color-ink-soft)] mb-1">
            <template x-for="d in dayNames" :key="d"><span x-text="d"></span></template>
        </div>
        <div class="grid grid-cols-7 gap-1">
            <template x-for="n in leadingBlanks" :key="'blank-' + n"><span></span></template>
            <template x-for="day in daysGrid" :key="day">
                <button type="button" @click="select(day)" :disabled="isDisabled(day)"
                    class="site-dp-day"
                    :class="{ 'site-dp-day-selected': isSelected(day), 'site-dp-day-today': isToday(day) && !isSelected(day), 'site-dp-day-disabled': isDisabled(day) }"
                    x-text="day"></button>
            </template>
        </div>

        <div class="flex items-center justify-between mt-3 pt-3 border-t border-[var(--color-line)]">
            <button type="button" @click="goToday()" class="text-xs text-[var(--color-primary)] font-medium hover:underline">Ke hari ini</button>
            <button type="button" @click="clear()" class="text-xs text-[var(--color-ink-soft)] hover:text-[var(--color-danger)]">Hapus tanggal</button>
        </div>
    </div>
</div>
