@extends('layouts.site')

@section('title', 'Notifikasi')

@section('content')
    <section class="max-w-3xl mx-auto px-5 sm:px-8 py-12 sm:py-16">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
            <div>
                <span class="text-xs uppercase tracking-wide text-[var(--color-primary)]">Notifikasi</span>
                <h1 class="font-display text-2xl sm:text-3xl mt-2">Pemberitahuan Untuk Anda</h1>
            </div>
            <div class="flex items-center gap-3">
                @if (Auth::user()->unreadNotifications()->count() > 0)
                    <form method="POST" action="{{ route('customer.notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-full border border-[var(--color-line)] text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition">
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
                <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        @if ($notifications->isEmpty())
            <x-site.empty-state
                title="Belum ada notifikasi"
                description="Kabar terbaru soal verifikasi dokumen dan pembayaran Anda akan muncul di sini." />
        @else
            <div class="rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] divide-y divide-[var(--color-line)] overflow-hidden">
                @foreach ($notifications as $notification)
                    @php $data = $notification->data; @endphp
                    <form method="POST" action="{{ route('customer.notifications.read', $notification) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="redirect" value="1">
                        <button type="submit" class="w-full text-left flex gap-3 px-5 py-4 hover:bg-[var(--color-paper)] transition-colors {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <span @class([
                                'mt-0.5 w-9 h-9 rounded-full flex items-center justify-center shrink-0',
                                'bg-[var(--color-warning-ink)]/10 text-[var(--color-warning-ink)]' => ($data['icon'] ?? '') === 'warning',
                                'bg-[var(--color-success)]/10 text-[var(--color-success)]' => ($data['icon'] ?? '') === 'success',
                                'bg-[var(--color-danger)]/10 text-[var(--color-danger)]' => ($data['icon'] ?? '') === 'danger',
                                'bg-[var(--color-info)]/10 text-[var(--color-info)]' => !in_array($data['icon'] ?? '', ['warning', 'success', 'danger']),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-2">
                                    <span class="font-medium">{{ $data['title'] ?? 'Notifikasi' }}</span>
                                    @if (!$notification->read_at)
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--color-primary)]"></span>
                                    @endif
                                </span>
                                <span class="block text-sm text-[var(--color-ink-soft)] mt-0.5">{{ $data['message'] ?? '' }}</span>
                                <span class="block text-xs text-[var(--color-ink-soft)] mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                            </span>
                        </button>
                    </form>
                @endforeach
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </section>
@endsection
