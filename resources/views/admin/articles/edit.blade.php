@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')

    <a href="{{ route('admin.articles.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1.5">Judul Artikel</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}"
                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('title') border-[var(--color-danger)] @enderror">
                @error('title') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Isi Artikel</label>
                <textarea name="content" rows="8"
                          class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('content') border-[var(--color-danger)] @enderror">{{ old('content', $article->content) }}</textarea>
                @error('content') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Foto Sampul</label>

                @if ($article->thumbnail)
                    <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-32 h-20 object-cover rounded-lg mb-2 border border-[var(--color-line)]" alt="{{ $article->title }}">
                @endif

                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm">
                @error('thumbnail') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Meta Title <span class="text-[var(--color-ink-soft)] font-normal">(SEO)</span></label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Meta Description <span class="text-[var(--color-ink-soft)] font-normal">(SEO)</span></label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $article->meta_description) }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published)) class="rounded border-[var(--color-line)] text-[var(--color-primary)]">
                Terbitkan
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">Simpan Perubahan</button>
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-[var(--color-line)] hover:bg-[var(--color-paper)]">Batal</a>
            </div>
        </form>
    </div>

@endsection
