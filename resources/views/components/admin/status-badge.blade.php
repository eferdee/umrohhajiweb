@props(['status', 'label' => null, 'variant' => null])

@php
    // Peta status -> varian warna. Bisa dioverride manual lewat prop $variant.
    $map = [
        'success' => ['paid', 'completed', 'verified', 'active', 1, true, 'published', 'terverifikasi', 'replied'],
        'warning' => ['pending', 'waiting_payment', 'waiting_verification', 'partially_paid', 'scheduled', 'new'],
        'danger'  => ['cancelled', 'rejected', 'incomplete', 0, false],
        'info'    => ['refunded', 'read', 'closed'],
    ];

    $resolved = $variant;
    if (! $resolved) {
        foreach ($map as $key => $values) {
            if (in_array($status, $values, true)) {
                $resolved = $key;
                break;
            }
        }
    }
    $resolved = $resolved ?? 'neutral';

    $text = $label ?? ucfirst(str_replace('_', ' ', (string) $status));
@endphp

<span {{ $attributes->merge(['class' => "badge badge-{$resolved}"]) }}>{{ $text }}</span>
