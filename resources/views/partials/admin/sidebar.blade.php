@php
    // Menu dikelompokkan hanya untuk kebutuhan tampilan — route, nama, dan
    // urutan resource tidak berubah dari versi sebelumnya.
    $menuGroups = [
        'Utama' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'grid'],
        ],
        'Operasional' => [
            ['label' => 'Paket', 'route' => 'admin.packages.index', 'active' => 'admin.packages.*', 'icon' => 'box'],
            ['label' => 'Booking', 'route' => 'admin.bookings.index', 'active' => 'admin.bookings.*', 'icon' => 'calendar'],
            ['label' => 'Jamaah', 'route' => 'admin.pilgrims.index', 'active' => 'admin.pilgrims.*', 'icon' => 'users'],
            ['label' => 'Pembayaran', 'route' => 'admin.payments.index', 'active' => 'admin.payments.*', 'icon' => 'card'],
            ['label' => 'Pesan Masuk', 'route' => 'admin.contacts.index', 'active' => 'admin.contacts.*', 'icon' => 'mail'],
        ],
        'Konten' => [
            ['label' => 'Artikel', 'route' => 'admin.articles.index', 'active' => 'admin.articles.*', 'icon' => 'document'],
            ['label' => 'Galeri', 'route' => 'admin.gallery.index', 'active' => 'admin.gallery.*', 'icon' => 'photo'],
            ['label' => 'FAQ', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'icon' => 'question'],
        ],
        'Sistem' => [
            ['label' => 'Pengaturan', 'route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'icon' => 'cog'],
        ],
    ];

    $icons = [
        'grid' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z',
        'box' => 'M3.75 9.75l8.25-4.5 8.25 4.5m-16.5 0l8.25 4.5m-8.25-4.5v7.5l8.25 4.5m0-12v12m0-12l8.25-4.5m-8.25 16.5l8.25-4.5v-7.5',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75V19.5a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75V6.75A.75.75 0 014.5 6zM8.5 12.5l2 2 4-4',
        'users' => 'M15 19.5a3 3 0 00-6 0M12 12.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5zM3 19.5c.5-3 3-5.25 6-5.25M21 19.5c-.5-3-3-5.25-6-5.25M17.25 10.5a2.625 2.625 0 100-5.25 2.625 2.625 0 000 5.25zM6.75 10.5a2.625 2.625 0 100-5.25 2.625 2.625 0 000 5.25z',
        'card' => 'M2.25 8.25h19.5M4.5 5.25h15a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-9A2.25 2.25 0 014.5 5.25zM6 15h4.5',
        'mail' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m5.231 0H8.25a2.25 2.25 0 00-2.25 2.25v15a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9m-6-8.25L18.5 8.5M9 15h6M9 18h3.75',
        'photo' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 4.5h18A1.5 1.5 0 0122.5 6v12a1.5 1.5 0 01-1.5 1.5H3A1.5 1.5 0 011.5 18V6A1.5 1.5 0 013 4.5zM9.75 9.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z',
        'question' => 'M9.879 7.519a3 3 0 015.657 1.897c0 1.5-1.5 2.25-2.379 2.917-.5.38-.657.844-.657 1.417M12 17h.01M12 21a9 9 0 100-18 9 9 0 000 18z',
        'cog' => 'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.164.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.766.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.164-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.765-.781-.929-.398-.165-.854-.143-1.204.107l-.738.527a1.125 1.125 0 01-1.449-.12l-.773-.774a1.125 1.125 0 01-.12-1.449l.527-.738c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15a1.125 1.125 0 01-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.425-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.164.71-.505.78-.929l.15-.894zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
    ];
@endphp

<aside
    class="fixed left-0 top-0 h-screen w-64 z-40 flex flex-col
           bg-gradient-to-b from-[var(--color-primary)] to-[var(--color-primary-dark)]
           text-white shadow-xl transition-transform duration-200
           -translate-x-full lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
        <img src="{{ asset('images/logo-white.png') }}" alt="{{ \App\Models\Setting::get('site_name', 'Travel') }}" class="w-9 h-9 object-contain shrink-0">
        <div>
            <p class="font-display text-lg leading-tight tracking-wide">{{ \App\Models\Setting::get('site_name', 'Travel Admin') }}</p>
            <p class="text-[11px] uppercase tracking-[0.2em] text-white/50">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto sidebar-scroll px-6 py-6 space-y-6" aria-label="Navigasi admin">
        @foreach ($menuGroups as $groupLabel => $menus)
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">{{ $groupLabel }}</p>
                <div class="space-y-1">
                    @foreach ($menus as $menu)
                        @php $isActive = request()->routeIs($menu['active']); @endphp
                        <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}"
                           @if ($isActive) aria-current="page" @endif
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
                                  {{ $isActive ? 'bg-white/10 text-white font-medium nav-active-indicator' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$menu['icon']] }}" />
                            </svg>
                            <span>{{ $menu['label'] }}</span>
                            @if ($isActive)
                                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-[var(--color-gold)]"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="px-6 py-5 border-t border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-sm font-medium">
                {{ Str::substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-[11px] text-white/50 truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
    </div>
</aside>
