@extends('layouts.admin')

@section('title', 'Tambah Item Galeri')

@section('content')

    <a href="{{ route('admin.gallery.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-xl">
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('title') border-[var(--color-danger)] @enderror">
                @error('title') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Tipe</label>
                    <select name="type" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('type') border-[var(--color-danger)] @enderror">
                        <option value="image" @selected(old('type') == 'image')>Foto</option>
                        <option value="video" @selected(old('type') == 'video')>Video</option>
                    </select>
                    @error('type') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">File (Foto / Video)</label>
                <input type="file" name="file" class="w-full text-sm">
                @error('file') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                    Tandai sebagai item unggulan
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                    Tampilkan di halaman publik
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan</button>
                <a href="{{ route('admin.gallery.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
