@extends('layouts.admin')

@section('title', 'Pembayaran')

@section('content')

    @php
        $totalPending = \App\Models\Payment::where('status', 'pending')->count();
        $totalVerified = \App\Models\Payment::where('status', 'verified')->count();
        $totalRevenue = \App\Models\Payment::where('status', 'verified')->sum('amount');
        $totalRejected = \App\Models\Payment::where('status', 'rejected')->count();
    @endphp

    <x-admin.page-header title="Pembayaran" description="Verifikasi dan kelola pembayaran masuk dari jamaah." />

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card label="Menunggu Verifikasi" :value="$totalPending" accent="var(--color-warning-ink)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Terverifikasi" :value="$totalVerified" accent="var(--color-success)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Total Pendapatan" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" accent="var(--color-primary)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Ditolak" :value="$totalRejected" accent="var(--color-danger)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
    </div>

    <form method="GET" class="admin-toolbar p-4 mb-5">
        <select name="status" onchange="this.form.submit()" class="filter-select sm:w-64">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') == 'pending')>Menunggu Verifikasi</option>
            <option value="verified" @selected(request('status') == 'verified')>Terverifikasi</option>
            <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
            <option value="refunded" @selected(request('status') == 'refunded')>Refund</option>
        </select>
    </form>

    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pemesan</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td class="font-medium">{{ $payment->invoice_number }}</td>
                        <td>{{ $payment->booking->user->name ?? '-' }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="text-[var(--color-ink-soft)]">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                        <td><x-admin.status-badge :status="$payment->status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="action-icon-btn" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($payments->isEmpty())
            <x-admin.empty-state title="Belum ada pembayaran masuk" description="Pembayaran dari jamaah akan muncul di sini." />
        @endif
    </div>

    {{ $payments->links('components.admin.pagination') }}

@endsection
