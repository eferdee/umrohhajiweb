@props(['href', 'label' => 'Kembali'])

<a href="{{ $href }}" {{ $attributes->class(['reveal', 'site-back-link', 'mb-6']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
    {{ $label }}
</a>
