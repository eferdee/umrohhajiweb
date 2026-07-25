@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('content')

    <a href="{{ route('admin.packages.schedules.index', $package) }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-xl mt-4">
        <p class="text-xs text-[var(--color-gold-ink)] font-medium mb-1">{{ $package->title }}</p>

        <form method="POST" action="{{ route('admin.packages.schedules.store', $package) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5">Kota Keberangkatan</label>
                <input type="text" name="departure_city" value="{{ old('departure_city') }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('departure_city') border-[var(--color-danger)] @enderror">
                @error('departure_city') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Tanggal Berangkat</label>
                    <input type="date" name="departure_date" value="{{ old('departure_date') }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('departure_date') border-[var(--color-danger)] @enderror">
                    @error('departure_date') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Tanggal Kembali</label>
                    <input type="date" name="return_date" value="{{ old('return_date') }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('return_date') border-[var(--color-danger)] @enderror">
                    @error('return_date') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('price') border-[var(--color-danger)] @enderror">
                    @error('price') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Kuota</label>
                    <input type="number" name="quota" value="{{ old('quota') }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('quota') border-[var(--color-danger)] @enderror">
                    @error('quota') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="status" value="1" checked class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Aktifkan jadwal ini
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan</button>
                <a href="{{ route('admin.packages.schedules.index', $package) }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
