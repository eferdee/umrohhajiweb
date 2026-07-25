<header class="sticky top-0 z-20 bg-[var(--color-surface)]/90 backdrop-blur border-b border-[var(--color-admin-border)]">
    <div class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 gap-4">

        <div class="flex items-center gap-3 min-w-0">
            <!-- Tombol buka sidebar (mobile) -->
            <button @click="sidebarOpen = true" class="lg:hidden -ml-1 p-2 rounded-lg text-[var(--color-ink-soft)] hover:bg-[var(--color-admin-bg)]" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>

            <div class="min-w-0">
                <p class="text-[11px] uppercase tracking-[0.16em] text-[var(--color-ink-soft)]">Admin &middot; {{ now()->translatedFormat('l, d F Y') }}</p>
                <h1 class="font-display text-xl leading-tight truncate">@yield('title')</h1>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4" x-data="{ profileOpen: false, notifOpen: false }">

            @php
                $unreadContacts = \App\Models\ContactMessage::where('is_read', false)->latest()->take(5)->get();
                $pendingPayments = \App\Models\Payment::where('status', 'pending')->latest()->take(5)->get();
                $pendingBookings = \App\Models\Booking::whereIn('status', ['pending', 'waiting_verification'])->latest()->take(5)->get();
                // Notifikasi tersimpan: dokumen/pembayaran yang dikirim ulang customer setelah ditolak
                $recentNotifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();

                $notifCount = $unreadContacts->count() + $pendingPayments->count() + $pendingBookings->count() + $recentNotifications->count();
            @endphp

            <!-- Notification Center -->
            <div class="relative">
                <button @click="notifOpen = !notifOpen" :aria-expanded="notifOpen.toString()" aria-haspopup="true"
                        class="relative p-2 rounded-lg text-[var(--color-ink-soft)] hover:bg-[var(--color-admin-bg)] transition-colors">
                    <span class="sr-only">Buka pusat notifikasi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @if ($notifCount > 0)
                        <span class="absolute top-1 right-1 min-w-[16px] h-4 px-1 rounded-full bg-[var(--color-danger)] text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                    @endif
                </button>

                <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false" x-transition
                     class="absolute right-0 mt-2 w-[22rem] max-w-[90vw] max-h-[26rem] overflow-y-auto surface-scroll bg-[var(--color-surface)] rounded-xl shadow-[var(--shadow-elevated)] border border-[var(--color-admin-border)] text-sm">
                    <div class="px-4 py-3 border-b border-[var(--color-admin-border)] flex items-center justify-between sticky top-0 bg-[var(--color-surface)]">
                        <span class="font-display text-base">Pusat Notifikasi</span>
                        @if ($notifCount > 0)
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-[var(--color-danger)]/10 text-[var(--color-danger)] font-medium">{{ $notifCount }} baru</span>
                        @endif
                    </div>

                    @foreach ($recentNotifications as $notification)
                        @php $data = $notification->data; @endphp
                        <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="redirect" value="1">
                            <button type="submit" class="w-full text-left flex gap-3 px-4 py-3 border-b border-[var(--color-admin-border)] hover:bg-[var(--color-admin-bg)] transition-colors">
                                <span class="mt-0.5 w-8 h-8 rounded-full bg-[var(--color-warning-ink)]/10 text-[var(--color-warning-ink)] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                                </span>
                                <span class="min-w-0">
                                    <p class="font-medium truncate">{{ $data['title'] ?? 'Notifikasi' }}</p>
                                    <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $data['message'] ?? '' }}</p>
                                </span>
                            </button>
                        </form>
                    @endforeach

                    @foreach ($unreadContacts as $contact)
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="flex gap-3 px-4 py-3 border-b border-[var(--color-admin-border)] hover:bg-[var(--color-admin-bg)] transition-colors">
                            <span class="mt-0.5 w-8 h-8 rounded-full bg-[var(--color-info-soft)] text-[var(--color-info)] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            </span>
                            <span class="min-w-0">
                                <p class="font-medium truncate">Pesan baru dari {{ $contact->name }}</p>
                                <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $contact->subject }}</p>
                            </span>
                        </a>
                    @endforeach

                    @foreach ($pendingPayments as $payment)
                        <a href="{{ route('admin.payments.show', $payment) }}" class="flex gap-3 px-4 py-3 border-b border-[var(--color-admin-border)] hover:bg-[var(--color-admin-bg)] transition-colors">
                            <span class="mt-0.5 w-8 h-8 rounded-full bg-[var(--color-warning-ink)]/10 text-[var(--color-warning-ink)] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M4.5 5.25h15a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-9A2.25 2.25 0 014.5 5.25zM6 15h4.5" /></svg>
                            </span>
                            <span class="min-w-0">
                                <p class="font-medium">Pembayaran menunggu verifikasi</p>
                                <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $payment->invoice_number }}</p>
                            </span>
                        </a>
                    @endforeach

                    @foreach ($pendingBookings as $booking)
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="flex gap-3 px-4 py-3 border-b border-[var(--color-admin-border)] hover:bg-[var(--color-admin-bg)] transition-colors">
                            <span class="mt-0.5 w-8 h-8 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75V19.5a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75V6.75A.75.75 0 014.5 6z" /></svg>
                            </span>
                            <span class="min-w-0">
                                <p class="font-medium">Booking menunggu konfirmasi</p>
                                <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $booking->booking_code ?? '#' . $booking->id }}</p>
                            </span>
                        </a>
                    @endforeach

                    @if ($notifCount === 0)
                        <p class="px-4 py-8 text-center text-[var(--color-ink-soft)]">Tidak ada notifikasi baru.</p>
                    @endif

                    <a href="{{ route('admin.notifications.index') }}" class="block text-center px-4 py-3 text-sm font-medium text-[var(--color-primary)] hover:bg-[var(--color-admin-bg)] sticky bottom-0 bg-[var(--color-surface)] border-t border-[var(--color-admin-border)]">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>

            <div class="w-px h-6 bg-[var(--color-admin-border)] hidden sm:block"></div>

            <div class="relative">
                <button @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-[var(--color-admin-bg)] transition-colors">
                    <div class="w-8 h-8 rounded-full bg-[var(--color-primary)] text-white flex items-center justify-center text-xs font-medium">
                        {{ Str::substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="hidden sm:inline text-sm font-medium">{{ Str::before(auth()->user()->name ?? 'Admin', ' ') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4 text-[var(--color-ink-soft)]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false" x-transition
                     class="absolute right-0 mt-2 w-48 bg-[var(--color-surface)] rounded-xl shadow-[var(--shadow-elevated)] border border-[var(--color-admin-border)] py-2 text-sm">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-[var(--color-admin-bg)]">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-[var(--color-danger)] hover:bg-[var(--color-admin-bg)]">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
