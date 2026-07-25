<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') — {{ \App\Models\Setting::get('site_name', 'Travel Admin') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="admin-panel bg-[var(--color-admin-bg)] text-[var(--color-ink)] antialiased" x-data="{ sidebarOpen: false }">

    <x-admin.toast />

    @include('partials.admin.sidebar')

    <!-- Overlay gelap saat sidebar dibuka di layar kecil -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-transition.opacity></div>

    <div class="lg:ml-64 min-h-screen flex flex-col">

        @include('partials.admin.navbar')

        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1600px] w-full mx-auto">
            @yield('content')
        </main>

        @include('partials.admin.footer')

    </div>

    @stack('scripts')

</body>
</html>
