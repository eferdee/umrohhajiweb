@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    @php
        use App\Models\Booking;
        use App\Models\Payment;
        use App\Models\PackageSchedule;
        use App\Models\Article;
        use App\Models\ContactMessage;
        use App\Models\BookingPilgrim;
        use Carbon\Carbon;

        // --- KPI tambahan (read-only, tidak menyentuh controller/model) ---
        $totalJamaah = BookingPilgrim::count();

        $revenueThisMonth = Payment::where('status', 'verified')
            ->whereBetween('payment_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $revenueLastMonth = Payment::where('status', 'verified')
            ->whereBetween('payment_date', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])
            ->sum('amount');
        $revenueTrend = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        $bookingsThisMonth = Booking::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $bookingsLastMonth = Booking::whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])->count();
        $bookingTrend = $bookingsLastMonth > 0
            ? round((($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth) * 100)
            : ($bookingsThisMonth > 0 ? 100 : 0);

        // --- Grafik 6 bulan terakhir: booking & pendapatan terverifikasi ---
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonthsNoOverflow($i)->startOfMonth());
        $chartLabels = $months->map(fn ($m) => $m->translatedFormat('M Y'))->values();
        $chartBookings = $months->map(fn ($m) => Booking::whereBetween('created_at', [$m->copy()->startOfMonth(), $m->copy()->endOfMonth()])->count())->values();
        $chartRevenue = $months->map(fn ($m) => (float) Payment::where('status', 'verified')->whereBetween('payment_date', [$m->copy()->startOfMonth(), $m->copy()->endOfMonth()])->sum('amount'))->values();

        // --- Distribusi status booking (untuk donut) ---
        $statusLabels = ['pending' => 'Pending', 'waiting_payment' => 'Menunggu Bayar', 'waiting_verification' => 'Verifikasi', 'partially_paid' => 'DP Terbayar', 'paid' => 'Lunas', 'scheduled' => 'Terjadwal', 'completed' => 'Selesai', 'cancelled' => 'Batal'];
        $statusCounts = Booking::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $donutLabels = $statusCounts->keys()->map(fn ($s) => $statusLabels[$s] ?? ucfirst($s))->values();
        $donutData = $statusCounts->values();

        // --- Activity Timeline: gabungan aktivitas terbaru ---
        $timeline = collect()
            ->concat(Booking::with('user')->latest()->take(4)->get()->map(fn ($b) => [
                'type' => 'booking', 'title' => 'Booking baru — ' . ($b->user->name ?? 'Jamaah'),
                'subtitle' => $b->booking_code ?? '#' . $b->id, 'time' => $b->created_at, 'color' => 'var(--color-primary)',
            ]))
            ->concat(Payment::latest()->take(4)->get()->map(fn ($p) => [
                'type' => 'payment', 'title' => 'Pembayaran ' . ($p->status === 'verified' ? 'terverifikasi' : 'masuk'),
                'subtitle' => $p->invoice_number, 'time' => $p->created_at, 'color' => 'var(--color-gold-deep)',
            ]))
            ->concat(ContactMessage::latest()->take(3)->get()->map(fn ($c) => [
                'type' => 'contact', 'title' => 'Pesan kontak — ' . $c->name,
                'subtitle' => $c->subject, 'time' => $c->created_at, 'color' => 'var(--color-info)',
            ]))
            ->sortByDesc('time')
            ->take(7);

        // --- Kalender mini: jadwal keberangkatan bulan ini ---
        $calMonth = now()->startOfMonth();
        $calDays = $calMonth->daysInMonth;
        $calStartOffset = ($calMonth->copy()->startOfMonth()->dayOfWeekIso) % 7; // 0=Senin
        $departureDays = PackageSchedule::where('status', true)
            ->whereBetween('departure_date', [$calMonth->copy()->startOfMonth(), $calMonth->copy()->endOfMonth()])
            ->pluck('departure_date')->map(fn ($d) => Carbon::parse($d)->day)->unique();
        $upcomingDepartures = PackageSchedule::with('package')->where('status', true)
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date')->take(5)->get();

        // --- Notification Center (ringkasan) ---
        $unreadContacts = ContactMessage::where('is_read', false)->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $pendingBookings = Booking::whereIn('status', ['pending', 'waiting_verification'])->count();

        // --- System Health: sinyal operasional dari data yang sudah ada ---
        $lowSeatSchedules = PackageSchedule::where('status', true)->whereColumn('available_seat', '<=', 'quota')->where('available_seat', '<=', 5)->count();
        $overduePayments = Booking::whereIn('status', ['pending', 'waiting_payment'])->where('payment_deadline', '<', now())->count();
        $healthChecks = [
            ['label' => 'Antrean verifikasi pembayaran', 'value' => $pendingPayments, 'status' => $pendingPayments === 0 ? 'ok' : ($pendingPayments <= 5 ? 'warn' : 'bad')],
            ['label' => 'Booking menunggu tindak lanjut', 'value' => $pendingBookings, 'status' => $pendingBookings === 0 ? 'ok' : ($pendingBookings <= 5 ? 'warn' : 'bad')],
            ['label' => 'Batas bayar terlewat', 'value' => $overduePayments, 'status' => $overduePayments === 0 ? 'ok' : 'bad'],
            ['label' => 'Jadwal hampir penuh (sisa ≤5 kursi)', 'value' => $lowSeatSchedules, 'status' => $lowSeatSchedules === 0 ? 'ok' : 'warn'],
            ['label' => 'Pesan kontak belum dibaca', 'value' => $unreadContacts, 'status' => $unreadContacts === 0 ? 'ok' : 'warn'],
        ];
        $healthScore = collect($healthChecks)->where('status', 'ok')->count();
    @endphp

    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm text-[var(--color-ink-soft)]">Selamat datang kembali, {{ Str::before(auth()->user()->name ?? 'Admin', ' ') }} 👋</p>
            <h2 class="font-display text-2xl mt-1">Ringkasan hari ini</h2>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-primary)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Total Paket</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center text-[var(--color-primary)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75l8.25-4.5 8.25 4.5m-16.5 0l8.25 4.5m-8.25-4.5v7.5l8.25 4.5m0-12v12m0-12l8.25-4.5m-8.25 16.5l8.25-4.5v-7.5" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl">{{ $stats['total_packages'] ?? 0 }}</p>
            <p class="text-xs text-[var(--color-success)] mt-1">{{ $stats['active_packages'] ?? 0 }} aktif</p>
        </div>

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-gold-deep)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Total Booking</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-gold-deep)]/15 flex items-center justify-center text-[var(--color-gold-ink)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75V19.5a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75V6.75A.75.75 0 014.5 6z" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl">{{ $stats['total_bookings'] ?? 0 }}</p>
            <p class="text-xs mt-1 {{ $bookingTrend >= 0 ? 'text-[var(--color-success)]' : 'text-[var(--color-danger)]' }}">
                {{ $bookingTrend >= 0 ? '↑' : '↓' }} {{ abs($bookingTrend) }}% vs bulan lalu
            </p>
        </div>

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-success)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Pendapatan Bulan Ini</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-success)]/10 flex items-center justify-center text-[var(--color-success)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3.75-9.75h5.25a1.875 1.875 0 010 3.75h-3a1.875 1.875 0 000 3.75h5.25M6 21h12a2.25 2.25 0 002.25-2.25V5.25A2.25 2.25 0 0018 3H6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 006 21z" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-2xl">Rp{{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
            <p class="text-xs mt-1 {{ $revenueTrend >= 0 ? 'text-[var(--color-success)]' : 'text-[var(--color-danger)]' }}">
                {{ $revenueTrend >= 0 ? '↑' : '↓' }} {{ abs($revenueTrend) }}% vs bulan lalu
            </p>
        </div>

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-info)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Total Jamaah</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-info-soft)] flex items-center justify-center text-[var(--color-info)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5a3 3 0 00-6 0M12 12.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5zM3 19.5c.5-3 3-5.25 6-5.25M21 19.5c-.5-3-3-5.25-6-5.25" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl">{{ $totalJamaah }}</p>
            <p class="text-xs text-[var(--color-ink-soft)] mt-1">Terdaftar di semua booking</p>
        </div>

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-primary)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Total Artikel</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center text-[var(--color-primary)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m5.231 0H8.25a2.25 2.25 0 00-2.25 2.25v15a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl">{{ $stats['total_articles'] ?? 0 }}</p>
            <p class="text-xs text-[var(--color-ink-soft)] mt-1">Dipublikasikan & draft</p>
        </div>

        <div class="kpi-card p-5" style="--kpi-accent: var(--color-danger)">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wide text-[var(--color-ink-soft)]">Pesan Belum Dibaca</span>
                <div class="w-9 h-9 rounded-full bg-[var(--color-danger)]/10 flex items-center justify-center text-[var(--color-danger)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl">{{ $stats['unread_contacts'] ?? 0 }}</p>
            <p class="text-xs text-[var(--color-ink-soft)] mt-1">Perlu ditindaklanjuti</p>
        </div>

    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
        <div class="dash-panel p-5 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-lg">Tren Booking &amp; Pendapatan</h3>
                <span class="text-xs text-[var(--color-ink-soft)]">6 bulan terakhir</span>
            </div>
            <div class="h-72">
                <canvas id="trendChart" role="img" aria-label="Grafik tren booking dan pendapatan 6 bulan terakhir"></canvas>
            </div>
        </div>

        <div class="dash-panel p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-lg">Status Booking</h3>
            </div>
            @if ($donutData->sum() > 0)
                <div class="h-72">
                    <canvas id="statusChart" role="img" aria-label="Distribusi status booking"></canvas>
                </div>
            @else
                <p class="text-sm text-[var(--color-ink-soft)] py-16 text-center">Belum ada data booking.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">

        {{-- Recent Booking --}}
        <div class="dash-panel overflow-hidden xl:col-span-2">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--color-admin-border)]">
                <h3 class="font-display text-lg">Booking Terbaru</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-[var(--color-primary)] hover:underline">Lihat semua &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[var(--color-ink-soft)] bg-[var(--color-admin-bg)]">
                            <th class="py-3 px-5 font-medium">Nama Jamaah</th>
                            <th class="py-3 px-5 font-medium">Paket</th>
                            <th class="py-3 px-5 font-medium">Status</th>
                            <th class="py-3 px-5 font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-admin-border)]">
                        @forelse ($latestBookings ?? [] as $booking)
                            <tr class="hover:bg-[var(--color-admin-bg)]/60 transition-colors">
                                <td class="py-3 px-5">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="hover:underline">{{ $booking->user->name ?? '-' }}</a>
                                </td>
                                <td class="py-3 px-5">{{ $booking->packageSchedule->package->title ?? '-' }}</td>
                                <td class="py-3 px-5">
                                    @php
                                        $statusColor = match ($booking->status) {
                                            'paid', 'completed' => 'bg-[var(--color-success)]/10 text-[var(--color-success)]',
                                            'cancelled' => 'bg-[var(--color-danger)]/10 text-[var(--color-danger)]',
                                            default => 'bg-[var(--color-warning-ink)]/10 text-[var(--color-warning-ink)]',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-[var(--color-ink-soft)]">{{ $booking->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-[var(--color-ink-soft)]">
                                    Belum ada booking masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="dash-panel p-5">
            <h3 class="font-display text-lg mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                @php
                    $quickActions = [
                        ['label' => 'Paket Baru', 'route' => 'admin.packages.create', 'icon' => 'M12 4.5v15m7.5-7.5h-15'],
                        ['label' => 'Booking', 'route' => 'admin.bookings.index', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75V19.5a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75V6.75A.75.75 0 014.5 6z'],
                        ['label' => 'Artikel Baru', 'route' => 'admin.articles.create', 'icon' => 'M12 4.5v15m7.5-7.5h-15'],
                        ['label' => 'Verifikasi Bayar', 'route' => 'admin.payments.index', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Pesan Masuk', 'route' => 'admin.contacts.index', 'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75'],
                        ['label' => 'Pengaturan', 'route' => 'admin.settings.index', 'icon' => 'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093'],
                    ];
                @endphp
                @foreach ($quickActions as $action)
                    @if (Route::has($action['route']))
                        <a href="{{ route($action['route']) }}"
                           class="flex flex-col items-start gap-2.5 p-3.5 rounded-xl border border-[var(--color-admin-border)] hover:border-[var(--color-primary)]/30 hover:bg-[var(--color-admin-bg)] transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-[var(--color-primary)]/10 text-[var(--color-primary)] flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}" />
                                </svg>
                            </span>
                            <span class="text-xs font-medium leading-tight">{{ $action['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">

        {{-- Activity Timeline --}}
        <div class="dash-panel p-5">
            <h3 class="font-display text-lg mb-4">Aktivitas Terbaru</h3>
            @if ($timeline->isEmpty())
                <p class="text-sm text-[var(--color-ink-soft)] py-6 text-center">Belum ada aktivitas.</p>
            @else
                <div class="timeline-list space-y-5">
                    @foreach ($timeline as $item)
                        <div class="timeline-item" style="--dot-color: {{ $item['color'] }}">
                            <p class="text-sm font-medium leading-snug">{{ $item['title'] }}</p>
                            <p class="text-xs text-[var(--color-ink-soft)] truncate">{{ $item['subtitle'] }}</p>
                            <p class="text-[11px] text-[var(--color-ink-soft)]/80 mt-0.5">{{ $item['time']->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Calendar --}}
        <div class="dash-panel p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-lg">Kalender Keberangkatan</h3>
                <span class="text-xs text-[var(--color-ink-soft)]">{{ $calMonth->translatedFormat('F Y') }}</span>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-[10px] text-[var(--color-ink-soft)] mb-1">
                @foreach (['Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb', 'Mg'] as $d)
                    <span>{{ $d }}</span>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-1 mb-4">
                @for ($i = 0; $i < $calStartOffset; $i++)
                    <span></span>
                @endfor
                @for ($d = 1; $d <= $calDays; $d++)
                    <span class="cal-day {{ $departureDays->contains($d) ? 'cal-day--has-event' : 'text-[var(--color-ink-soft)]' }} {{ $d === now()->day ? 'cal-day--today' : '' }}"
                          title="{{ $departureDays->contains($d) ? 'Ada keberangkatan' : '' }}">
                        {{ $d }}
                    </span>
                @endfor
            </div>
            <p class="text-[11px] uppercase tracking-wide text-[var(--color-ink-soft)] mb-2">Keberangkatan mendatang</p>
            <div class="space-y-2">
                @forelse ($upcomingDepartures as $schedule)
                    <div class="flex items-center justify-between text-xs">
                        <span class="truncate pr-2">{{ $schedule->package->title ?? '-' }}</span>
                        <span class="text-[var(--color-ink-soft)] shrink-0">{{ Carbon::parse($schedule->departure_date)->translatedFormat('d M') }}</span>
                    </div>
                @empty
                    <p class="text-xs text-[var(--color-ink-soft)]">Tidak ada jadwal mendatang.</p>
                @endforelse
            </div>
        </div>

        {{-- Notification Center (ringkasan) + System Health --}}
        <div class="space-y-5">
            <div class="dash-panel p-5">
                <h3 class="font-display text-lg mb-4">Ringkasan Notifikasi</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center justify-between">
                        <span class="text-[var(--color-ink-soft)]">Pesan belum dibaca</span>
                        <span class="font-semibold {{ $unreadContacts > 0 ? 'text-[var(--color-danger)]' : '' }}">{{ $unreadContacts }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-[var(--color-ink-soft)]">Pembayaran menunggu verifikasi</span>
                        <span class="font-semibold {{ $pendingPayments > 0 ? 'text-[var(--color-warning-ink)]' : '' }}">{{ $pendingPayments }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-[var(--color-ink-soft)]">Booking menunggu tindak lanjut</span>
                        <span class="font-semibold {{ $pendingBookings > 0 ? 'text-[var(--color-warning-ink)]' : '' }}">{{ $pendingBookings }}</span>
                    </li>
                </ul>
            </div>

            <div class="dash-panel p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-lg">Kesehatan Operasional</h3>
                    <span class="text-xs text-[var(--color-ink-soft)]">{{ $healthScore }}/{{ count($healthChecks) }} sehat</span>
                </div>
                <ul class="space-y-3 text-sm">
                    @foreach ($healthChecks as $check)
                        <li class="flex items-center gap-2.5">
                            <span class="health-dot health-{{ $check['status'] }}"></span>
                            <span class="flex-1 text-[var(--color-ink-soft)]">{{ $check['label'] }}</span>
                            <span class="font-semibold">{{ $check['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;

    const inkSoft = '#4b564f';
    Chart.defaults.font.family = "Inter, ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = inkSoft;

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Booking',
                        data: @json($chartBookings),
                        backgroundColor: '#050c69',
                        borderRadius: 6,
                        yAxisID: 'y',
                        order: 2,
                    },
                    {
                        label: 'Pendapatan (Rp)',
                        data: @json($chartRevenue),
                        type: 'line',
                        borderColor: '#b9922b',
                        backgroundColor: '#b9922b',
                        tension: 0.35,
                        yAxisID: 'y1',
                        order: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Booking' }, grid: { color: '#e5e4db' } },
                    y1: {
                        beginAtZero: true, position: 'right',
                        title: { display: true, text: 'Pendapatan' },
                        grid: { drawOnChartArea: false },
                        ticks: { callback: (v) => 'Rp' + (v / 1000000).toFixed(1) + 'jt' },
                    },
                },
            },
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($donutLabels),
                datasets: [{
                    data: @json($donutData),
                    backgroundColor: ['#050c69', '#b9922b', '#2a5c8a', '#2f6b4f', '#8f5c14', '#b3432f', '#4b564f', '#1420a0'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
            },
        });
    }
});
</script>
@endpush