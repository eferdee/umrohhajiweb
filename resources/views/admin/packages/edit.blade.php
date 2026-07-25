@extends('layouts.admin')

@section('title', 'Edit Paket')

@section('content')

    <a href="{{ route('admin.packages.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1.5">Kategori</label>
                <select name="category_id" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('category_id') border-[var(--color-danger)] @enderror">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $package->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Judul Paket</label>
                <input type="text" name="title" value="{{ old('title', $package->title) }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('title') border-[var(--color-danger)] @enderror">
                @error('title') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Maskapai</label>
                    <input type="text" name="airline" value="{{ old('airline', $package->airline) }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Hotel Makkah</label>
                    <input type="text" name="hotel_makkah" value="{{ old('hotel_makkah', $package->hotel_makkah) }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Hotel Madinah</label>
                    <input type="text" name="hotel_madinah" value="{{ old('hotel_madinah', $package->hotel_madinah) }}" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Durasi (hari)</label>
                <input type="number" name="duration" value="{{ old('duration', $package->duration) }}"
                       class="w-full sm:w-40 border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('duration') border-[var(--color-danger)] @enderror">
                @error('duration') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('description', $package->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Fasilitas</label>
                <textarea name="facilities" rows="3" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('facilities', $package->facilities) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Itinerary</label>
                <textarea name="itinerary" rows="4" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ old('itinerary', $package->itinerary) }}</textarea>
            </div>

            @if ($package->thumbnail)
                <div>
                    <p class="text-sm font-medium mb-1.5">Foto Saat Ini</p>
                    <img src="{{ asset('storage/' . $package->thumbnail) }}" class="w-32 h-20 object-cover rounded-lg">
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1.5">Ganti Foto Sampul</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="status" value="1" {{ old('status', $package->status) ? 'checked' : '' }} class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Aktifkan paket ini
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Perbarui</button>
                <a href="{{ route('admin.packages.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
