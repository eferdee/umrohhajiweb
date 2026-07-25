@php
    $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
    $unreadCount = $unreadNotifications->count();
@endphp

<div class="relative" x-data="{ notifOpen: false }">
    <button @click="notifOpen = !notifOpen" :aria-expanded="notifOpen.toString()" aria-haspopup="true"
            class="relative p-2 rounded-full text-[var(--color-ink-soft)] hover:bg-[var(--color-paper)] hover:text-[var(--color-primary)] transition-colors">
        <span class="sr-only">Buka notifikasi</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute top-0.5 right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-[var(--color-danger)] text-white text-[10px] font-bold flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false" x-transition
         class="absolute right-0 mt-2 w-[22rem] max-w-[90vw] max-h-[26rem] overflow-y-auto surface-scroll bg-[var(--color-surface)] rounded-xl shadow-[var(--shadow-elevated)] border border-[var(--color-line)] text-sm z-50">
        <div class="px-4 py-3 border-b border-[var(--color-line)] flex items-center justify-between sticky top-0 bg-[var(--color-surface)]">
            <span class="font-display text-base">Notifikasi</span>
            @if ($unreadCount > 0)
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-[var(--color-danger)]/10 text-[var(--color-danger)] font-medium">{{ $unreadCount }} baru</span>
            @endif
        </div>

        @forelse ($unreadNotifications as $notification)
            @php $data = $notification->data; @endphp
            <form method="POST" action="{{ route('customer.notifications.read', $notification) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="redirect" value="1">
                <button type="submit" class="w-full text-left flex gap-3 px-4 py-3 border-b border-[var(--color-line)] hover:bg-[var(--color-paper)] transition-colors">
                    <span @class([
                        'mt-0.5 w-8 h-8 rounded-full flex items-center justify-center shrink-0',
                        'bg-[var(--color-warning-ink)]/10 text-[var(--color-warning-ink)]' => ($data['icon'] ?? '') === 'warning',
                        'bg-[var(--color-success)]/10 text-[var(--color-success)]' => ($data['icon'] ?? '') === 'success',
                        'bg-[var(--color-danger)]/10 text-[var(--color-danger)]' => ($data['icon'] ?? '') === 'danger',
                        'bg-[var(--color-info)]/10 text-[var(--color-info)]' => !in_array($data['icon'] ?? '', ['warning', 'success', 'danger']),
                    ])>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <span class="min-w-0">
                        <p class="font-medium truncate">{{ $data['title'] ?? 'Notifikasi' }}</p>
                        <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $data['message'] ?? '' }}</p>
                    </span>
                </button>
            </form>
        @empty
            <p class="px-4 py-8 text-center text-[var(--color-ink-soft)]">Tidak ada notifikasi baru.</p>
        @endforelse

        <a href="{{ route('customer.notifications.index') }}" class="block text-center px-4 py-3 text-sm font-medium text-[var(--color-primary)] hover:bg-[var(--color-paper)] sticky bottom-0 bg-[var(--color-surface)] border-t border-[var(--color-line)]">
            Lihat semua notifikasi
        </a>
    </div>
</div>
