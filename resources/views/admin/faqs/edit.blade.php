@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')

    <a href="{{ route('admin.faqs.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $faq->category) }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Pertanyaan</label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('question') border-[var(--color-danger)] @enderror">
                @error('question') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Jawaban</label>
                <textarea name="answer" rows="5"
                          class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('answer') border-[var(--color-danger)] @enderror">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published)) class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Tampilkan di halaman publik
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan Perubahan</button>
                <a href="{{ route('admin.faqs.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
