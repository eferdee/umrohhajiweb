@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.package-categories.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 focus:border-[var(--color-primary)] @error('name') border-[var(--color-danger)] @enderror">
                @error('name') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 focus:border-[var(--color-primary)]">{{ old('description') }}</textarea>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="status" value="1" checked class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Aktifkan kategori ini
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan</button>
                <a href="{{ route('admin.package-categories.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
