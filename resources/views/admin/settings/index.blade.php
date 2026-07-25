@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')

    <x-admin.page-header title="Pengaturan" description="Kelola informasi kontak dan pengaturan umum untuk halaman publik." />

    @php
        $groupLabels = [
            'general' => 'Umum',
            'contact' => 'Kontak',
            'payment' => 'Pembayaran',
        ];

        $keyLabels = [
            'site_name' => 'Nama Situs',
            'site_tagline' => 'Tagline',
            'contact_phone' => 'Nomor Telepon',
            'contact_email' => 'Email',
            'contact_address' => 'Alamat',
            'operational_hours' => 'Jam Operasional',
            'bank_account' => 'Rekening Bank',
        ];

        $longFields = ['contact_address', 'site_tagline', 'bank_account'];
    @endphp

    @if (session('success'))
        <div class="bg-[var(--color-success)]/10 text-[var(--color-success)] px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 max-w-3xl">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $items)
            <div class="form-section overflow-hidden">
                <div class="form-section-header">
                    <h3 class="font-display text-lg">{{ $groupLabels[$group] ?? ucfirst($group) }}</h3>
                </div>

                <div class="p-6 space-y-5">
                    @foreach ($items as $item)
                        <div>
                            <label class="form-field-label">
                                {{ $keyLabels[$item->key] ?? ucwords(str_replace('_', ' ', $item->key)) }}
                            </label>

                            @if (in_array($item->key, $longFields))
                                <textarea name="settings[{{ $item->key }}]" rows="2"
                                          class="form-field-input">{{ old('settings.' . $item->key, $item->value) }}</textarea>
                            @else
                                <input type="text" name="settings[{{ $item->key }}]"
                                       value="{{ old('settings.' . $item->key, $item->value) }}"
                                       class="form-field-input">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">
                Simpan Pengaturan
            </button>
        </div>
    </form>

@endsection
