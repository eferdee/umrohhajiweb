@extends('layouts.admin')

@section('title', 'Tambah Paket')

@section('content')

    <a href="{{ route('admin.packages.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5">Kategori</label>
                <select name="category_id" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('category_id') border-[var(--color-danger)] @enderror">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Judul Paket</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('title') border-[var(--color-danger)] @enderror">
                @error('title') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Maskapai</label>
                    <input type="text" name="airline" value="{{ old('airline') }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Hotel Makkah</label>
                    <input type="text" name="hotel_makkah" value="{{ old('hotel_makkah') }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Hotel Madinah</label>
                    <input type="text" name="hotel_madinah" value="{{ old('hotel_madinah') }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Durasi (hari)</label>
                <input type="number" name="duration" value="{{ old('duration') }}"
                       class="w-full sm:w-40 border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('duration') border-[var(--color-danger)] @enderror">
                @error('duration') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Fasilitas</label>
                <textarea name="facilities" rows="3" placeholder="Satu fasilitas per baris" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('facilities') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Itinerary</label>
                <textarea name="itinerary" rows="4" placeholder="Rincian hari per hari" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('itinerary') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Foto Sampul</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm">
                @error('thumbnail') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="status" value="1" checked class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Aktifkan paket ini
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan</button>
                <a href="{{ route('admin.packages.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
