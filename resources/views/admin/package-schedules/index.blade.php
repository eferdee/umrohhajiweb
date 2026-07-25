@extends('layouts.admin')

@section('title', 'Jadwal — ' . $package->title)

@section('content')

    @php
        $totalActive = $package->schedules()->where('status', true)->count();
        $totalSeatLeft = $package->schedules()->sum('available_seat');
        $totalBookedSchedules = $package->schedules()->withCount('bookings')->get()->sum('bookings_count');
    @endphp

    <x-admin.page-header
        :title="$package->title"
        description="Kelola jadwal keberangkatan untuk paket ini."
        :back="route('admin.packages.index')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.packages.schedules.create', $package) }}"
               class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Jadwal
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if (session('error'))
        <div class="bg-[var(--color-danger)]/10 text-[var(--color-danger)] px-4 py-3 rounded-xl mb-6 text-sm border border-[var(--color-danger)]/20">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-3 gap-4 mb-6">
        <x-admin.stat-card label="Jadwal Aktif" :value="$totalActive" accent="var(--color-success)" />
        <x-admin.stat-card label="Sisa Kursi Total" :value="$totalSeatLeft" accent="var(--color-primary)" />
        <x-admin.stat-card label="Booking Masuk" :value="$totalBookedSchedules" accent="var(--color-gold-deep)" />
    </div>

    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kota Keberangkatan</th>
                    <th>Tanggal</th>
                    <th>Harga</th>
                    <th>Kursi</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->departure_city }}</td>
                        <td>{{ $schedule->departure_date->format('d M Y') }} &rarr; {{ $schedule->return_date->format('d M Y') }}</td>
                        <td>Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="{{ $schedule->available_seat <= 5 ? 'text-[var(--color-danger)] font-medium' : '' }}">{{ $schedule->available_seat }}</span> / {{ $schedule->quota }}
                        </td>
                        <td><x-admin.status-badge :status="$schedule->status" :label="$schedule->status ? 'Aktif' : 'Nonaktif'" /></td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.packages.schedules.edit', [$package, $schedule]) }}" class="action-icon-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                </a>
                                <form action="{{ route('admin.packages.schedules.destroy', [$package, $schedule]) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="action-icon-btn is-danger" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($schedules->isEmpty())
            <x-admin.empty-state title="Belum ada jadwal keberangkatan" description="Tambahkan jadwal agar paket ini bisa dipesan jamaah.">
                <x-slot:action>
                    <a href="{{ route('admin.packages.schedules.create', $package) }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">+ Tambah jadwal pertama</a>
                </x-slot:action>
            </x-admin.empty-state>
        @endif
    </div>

    {{ $schedules->links('components.admin.pagination') }}

@endsection
